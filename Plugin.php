<?php namespace Mrelevance\ScoutShopaholic;

use Mrelevance\ScoutShopaholic\Classes\Event\ExtendFieldHandler;
use Mrelevance\ScoutShopaholic\Classes\Event\ProductModelHandler;
use System\Classes\PluginBase;
use Event;

class Plugin extends PluginBase
{
    /** @var array Plugin dependencies */
    public $require = ['Lovata.Shopaholic', 'Lovata.Toolbox'];

    public function boot()
    {
        $this->addEventListener();
    }

    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    protected function addEventListener()
    {
        Event::subscribe(ExtendFieldHandler::class);
        //Event::subscribe(BrandModelHandler::class);
        //Event::subscribe(CategoryModelHandler::class);
        Event::subscribe(ProductModelHandler::class);
        //Event::subscribe(TagModelHandler::class);
    }
}
