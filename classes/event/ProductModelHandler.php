<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Collection\ProductCollection;
use Lovata\Shopaholic\Models\Product;
use MarketingRelevance\ScoutShopaholic\Behaviors\SearchScoutModel;
use MarketingRelevance\ScoutShopaholic\Classes\Helper\SearchHelper;

class ProductModelHandler
{
    public function subscribe()
    {
        Product::extend(function ($obModel) {
            /** @var Product $obModel */
            $obModel->fillable[] = 'search_synonym';
            $obModel->fillable[] = 'search_content';

            $obModel->implement[] = SearchScoutModel::class;
        });

        ProductCollection::extend(function ($obCollection) {
            /** @var ProductCollection $obCollection */
            $obCollection->addDynamicMethod('searchScout', function ($sSearch) use ($obCollection) {
                /** @var SearchHelper $obSearchHelper */

                $obSearchHelper = app(SearchHelper::class, [Product::class]);
                $arElementIDList = $obSearchHelper->result($sSearch);

                dd($arElementIDList);

                return $obCollection->intersect($arElementIDList);
            });
        });
    }
}
