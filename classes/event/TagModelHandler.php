<?php

namespace MarketingRelevance\ScoutShopaholic\Classes\Event;

use Lovata\Shopaholic\Models\Product;
use MarketingRelevance\ScoutShopaholic\Behaviors\SearchScoutModel;
use MarketingRelevance\ScoutShopaholic\Classes\Helper\SearchHelper;
use System\Classes\PluginManager;

class TagModelHandler
{
    public function subscribe()
    {
        if (!PluginManager::instance()->hasPlugin('Lovata.TagsShopaholic')) {
            return;
        }

        \Lovata\TagsShopaholic\Models\Tag::extend(function ($obModel) {
            /** @var \Lovata\TagsShopaholic\Models\Tag $obModel */
            $obModel->fillable[] = 'search_synonym';
            $obModel->fillable[] = 'search_content';

            $obModel->implement[] = SearchScoutModel::class;
        });

        \Lovata\TagsShopaholic\Classes\Collection\TagCollection::extend(function ($obCollection) {
            /** @var \Lovata\TagsShopaholic\Classes\Collection\TagCollection $obCollection */
            $obCollection->addDynamicMethod('searchScout', function ($sSearch) use ($obCollection) {
                /** @var SearchHelper $obSearchHelper */

                $obSearchHelper = app(SearchHelper::class, [Product::class]);
                $arElementIDList = $obSearchHelper->result($sSearch);

                return $obCollection->intersect($arElementIDList);
            });
        });
    }
}
