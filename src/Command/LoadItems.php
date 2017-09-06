<?php namespace Webas\AnalyticsWidgetExtension\Command;

use Anomaly\ConfigurationModule\Configuration\Contract\ConfigurationRepositoryInterface;
use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Anomaly\FilesModule\File\Contract\FileRepositoryInterface;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Spatie\Analytics\Analytics;
use Spatie\Analytics\AnalyticsClientFactory;
use Spatie\Analytics\Period;

class LoadItems
{

    use DispatchesJobs;

    /**
     * The widget instance.
     *
     * @var WidgetInterface
     */
    protected $widget;

    /**
     * Create a new LoadItems instance.
     *
     * @param WidgetInterface $widget
     */
    public function __construct(WidgetInterface $widget)
    {
        $this->widget = $widget;
    }

    /**
     * Handle the widget data.
     * @param FileRepositoryInterface $files
     * @param ConfigurationRepositoryInterface $configuration
     * @param File $
     */
    public function handle(FileRepositoryInterface $files, ConfigurationRepositoryInterface $configuration)
    {
        try {
            $analyticsData = $this->getAnalyticsData($files, $configuration);
        } catch (\Exception $e) {
            $this->widget->addData('errors', $e->getMessage());
            return;
        }

        if (! $analyticsData) {
            return;
        }

        $dates = $analyticsData->map(function ($item) {
            return (string) $item['date']->format('Y-m-d');
        });

        // $pages = \Analytics::fetchMostVisitedPages(Period::days(30));

        // Load the items to the widget's view data.
        $this->widget->addData('visitors', json_encode($analyticsData->pluck('visitors')->toArray()));
        $this->widget->addData('pageViews', json_encode($analyticsData->pluck('pageViews')->toArray()));
        $this->widget->addData('dates', json_encode($dates->toArray()));
    }

    private function getAnalyticsData($files, $configuration)
    {
        $days = $configuration->value(
            'webas.extension.analytics_widget::days',
            $this->widget->id,
            30
        );
        $view_id = $configuration->value(
            'webas.extension.analytics_widget::view_id',
            $this->widget->id
        );
        $auth_file_id = $configuration->value(
            'webas.extension.analytics_widget::auth_file',
            $this->widget->id
        );
        $file = null;

        if ($auth_file_id) {
            $file = $files->find($auth_file_id);
        }

        if (! $file or ! $view_id) {
            $this->widget->addData('errors', 'File or View ID not set.');
            return;
        }

        $analyticsConfig = [
            'view_id' => $view_id,
            'cache_lifetime_in_minutes' => 60 * 24,
            'service_account_credentials_json' => $file->url(),
            'cache_location' => storage_path('app/laravel-google-analytics/google-cache/'),
        ];

        $client = AnalyticsClientFactory::createForConfig($analyticsConfig);
        $analyticsClient = new Analytics($client, $analyticsConfig['view_id']);
        return $analyticsClient->fetchTotalVisitorsAndPageViews(Period::days($days));
    }
}
