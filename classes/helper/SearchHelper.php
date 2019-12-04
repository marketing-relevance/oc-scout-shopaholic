<?php

namespace Mrelevance\ScoutShopaholic\Classes\Helper;

use Laravel\Scout\Builder;
use Lovata\Toolbox\Traits\Helpers\TraitInitActiveLang;

/**
 * Class SearchHelper
 *
 * @package Mrelevance\ScoutShopaholic\Classes\Helper
 */
class SearchHelper
{
    use TraitInitActiveLang;

    /** @var string */
    protected $sModel;

    /** @var string */
    protected $sSearch;

    public function __construct(string $sModel)
    {
        $this->initActiveLang();

        $this->sModel = $sModel;
    }

    public function result(string $sSearch)
    {
        $sSearch = str_replace(' ', '+', $sSearch);
        $this->sSearch = trim($sSearch);

        if (! $this->validate()) {
            return null;
        }

        try {
            return $this->search()->get()->lists('id');
        } catch (\Exception $ex) {
            trace_log($ex);
        }
    }

    /**
     * @param null $callback
     * @return \Laravel\Scout\Builder
     */
    protected function search($callback = null)
    {
        return app(
            Builder::class,
            [
                'model' => $this->sModel::make(),
                'query' => $this->sSearch,
                'callback' => $callback,
                'softDelete' => config('scout.soft_delete', false),
            ]
        );
    }

    /**
     * Validate search model, search string, search settings.
     *
     * @return bool
     */
    protected function validate(): bool
    {
        // Check model class
        if (empty($this->sModel) || ! class_exists($this->sModel)) {
            return false;
        }

        // Check search string and search index
        if (empty($this->sSearch)) {
            return false;
        }

        return true;
    }
}
