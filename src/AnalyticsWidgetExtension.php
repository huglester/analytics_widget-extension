<?php namespace Webas\AnalyticsWidgetExtension;

use Anomaly\DashboardModule\Widget\Contract\WidgetInterface;
use Anomaly\DashboardModule\Widget\Extension\WidgetExtension;
use Webas\AnalyticsWidgetExtension\Command\LoadItems;

class AnalyticsWidgetExtension extends WidgetExtension
{

    /**
     * @var string
     */
    protected $provides = 'anomaly.module.dashboard::widget.analytics';

    /**
     * Load the widget data.
     *
     * @param WidgetInterface $widget
     */
    protected function load(WidgetInterface $widget)
    {
        $this->dispatch(new LoadItems($widget));
    }
}
