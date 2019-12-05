<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Event;

use Lovata\Shopaholic\Controllers\Products;
use Lovata\Shopaholic\Models\Product;

class ExtendFieldHandler
{
    /**
     * Add listeners.
     *
     * @param \Illuminate\Events\Dispatcher $obEvent
     */
    public function subscribe($obEvent)
    {
        $obEvent->listen('backend.form.extendFields', function ($obWidget) {
            $this->extendProductFields($obWidget);
        });
    }

    /**
     * Extend product fields.
     *
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function extendProductFields($obWidget)
    {
        // Only for the Products controller
        if (! $obWidget->getController() instanceof Products || $obWidget->isNested || empty($obWidget->context)) {
            return;
        }

        // Only for the Product model
        if (! $obWidget->model instanceof Product) {
            return;
        }

        $this->addSearchField($obWidget);
    }

    /**
     * Add search_synonym field.
     *
     * @param \Backend\Widgets\Form $obWidget
     */
    protected function addSearchField(\Backend\Widgets\Form $obWidget)
    {
        $obWidget->addTabFields([
            'search_synonym' => [
                'label' => 'Search Synonym',
                'tab' => 'Search',
                'span' => 'full',
                'type' => 'textarea',
            ],
        ]);
    }
}
