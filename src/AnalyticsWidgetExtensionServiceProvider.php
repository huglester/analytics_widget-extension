<?php namespace Webas\AnalyticsWidgetExtension;

use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Anomaly\DashboardModule\Widget\Extension\WidgetExtension;
use Anomaly\Streams\Platform\Addon\AddonServiceProvider;
use Webas\AnalyticsWidgetExtension\Command\LoadItems;

class AnalyticsWidgetExtensionServiceProvider extends AddonServiceProvider
{

    public function register()
    {
        /*$analyticsConfig = config('laravel-analytics');
        dd($analyticsConfig);

        $this->app->bind(AnalyticsClient::class, function () use ($analyticsConfig) {
            return AnalyticsClientFactory::createForConfig($analyticsConfig);
        });

        $this->app->bind(Analytics::class, function () use ($analyticsConfig) {
            $this->guardAgainstInvalidConfiguration($analyticsConfig);

            $client = app(AnalyticsClient::class);

            return new Analytics($client, $analyticsConfig['view_id']);
        });

        $this->app->alias(Analytics::class, 'laravel-analytics');*/
    }
}
