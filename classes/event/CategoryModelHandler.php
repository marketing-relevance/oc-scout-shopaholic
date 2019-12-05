<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Event;

use Lovata\Shopaholic\Classes\Collection\CategoryCollection;
use Lovata\Shopaholic\Models\Category;
use MarketingRelevance\ScoutShopaholic\Behaviors\SearchScoutModel;
use MarketingRelevance\ScoutShopaholic\Classes\Helper\SearchHelper;

class CategoryModelHandler
{
    public function subscribe()
    {
        Category::extend(function ($obModel) {
            /** @var Category $obModel */
            $obModel->fillable[] = 'search_synonym';
            $obModel->fillable[] = 'search_content';

            $obModel->implement[] = SearchScoutModel::class;
        });

        CategoryCollection::extend(function ($obCollection) {
            /** @var CategoryCollection $obCollection */
            $obCollection->addDynamicMethod('searchScout', function ($sSearch) use ($obCollection) {
                /** @var SearchHelper $obSearchHelper */

                $obSearchHelper = app(SearchHelper::class, [Category::class]);
                $arElementIDList = $obSearchHelper->result($sSearch);

                return $obCollection->intersect($arElementIDList);
            });
        });
    }
}
