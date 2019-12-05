<?php namespace MarketingRelevance\ScoutShopaholic;

use Event;
use System\Classes\PluginBase;

class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Shopaholic', 'Lovata.Toolbox'];

    public function boot()
    {
        $this->addEventListener();
    }

    /**
     * Add event listeners.
     */
    protected function addEventListener()
    {
        Event::subscribe(Classes\Event\ExtendFieldHandler::class);
        Event::subscribe(Classes\Event\BrandModelHandler::class);
        Event::subscribe(Classes\Event\CategoryModelHandler::class);
        Event::subscribe(Classes\Event\ProductModelHandler::class);
        Event::subscribe(Classes\Event\TagModelHandler::class);
    }
}
