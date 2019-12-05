<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Collection\BrandCollection;
use Lovata\Shopaholic\Models\Brand;
use MarketingRelevance\ScoutShopaholic\Behaviors\SearchScoutModel;
use MarketingRelevance\ScoutShopaholic\Classes\Helper\SearchHelper;

class BrandModelHandler
{
    public function subscribe()
    {
        Brand::extend(function ($obModel) {
            /** @var Brand $obModel */
            $obModel->fillable[] = 'search_synonym';
            $obModel->fillable[] = 'search_content';

            $obModel->implement[] = SearchScoutModel::class;
        });

        BrandCollection::extend(function ($obCollection) {
            /** @var BrandCollection $obCollection */
            $obCollection->addDynamicMethod('searchScout', function ($sSearch) use ($obCollection) {
                /** @var SearchHelper $obSearchHelper */

                $obSearchHelper = app(SearchHelper::class, [Brand::class]);
                $arElementIDList = $obSearchHelper->result($sSearch);

                return $obCollection->intersect($arElementIDList);
            });
        });
    }
}
