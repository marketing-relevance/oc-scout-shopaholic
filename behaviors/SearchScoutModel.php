<?php

namespace MarketingRelevance\ScoutShopaholic\Behaviors;

use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Builder;
use Laravel\Scout\EngineManager;
use Laravel\Scout\Jobs\MakeSearchable;
use Laravel\Scout\ModelObserver;
use System\Classes\ModelBehavior;
use Illuminate\Support\Collection as BaseCollection;

class SearchScoutModel extends ModelBehavior
{
    /** @var array  */
    protected $scoutMetadata = [];

    public function __construct($model)
    {
        parent::__construct($model);

        $this->registerSearchableMacros();
    }

    public function registerSearchableMacros()
    {
        $self = $this;

        BaseCollection::macro('searchable', function () use ($self) {
            $self->queueMakeSearchable($this->model);
        });

        BaseCollection::macro('unsearchable', function () use ($self) {
            $self->queueRemoveFromSearch($this->model);
        });
    }

    /**
     * Dispatch the job to make the given models searchable.
     *
     * @param \Illuminate\Database\Eloquent\Collection  $models
     */
    public function queueMakeSearchable($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        if (! config('scout.queue')) {
            return $models->first()->searchableUsing()->update($models);
        }

        dispatch(
            (new MakeSearchable($models))
            ->onQueue($models->first()->syncWithSearchUsingQueue())
            ->onConnection($models->first()->syncWithSearchUsing())
        );
    }

    /**
     * @param \Illuminate\Database\Eloquent\Collection  $models
     */
    public function queueRemoveFromSearch($models)
    {
        if ($models->isEmpty()) {
            return;
        }

        return $models->first()->searchableUsing()->delete($models);
    }

    /**
     * Determine if the model should be searchable.
     *
     * @return bool
     */
    public function shouldBeSearchable(): bool
    {
        return true;
    }

    public static function search($query = '', $callback = null)
    {
        return app(
            Builder::class, [
                'model' => static::make(),
                'query' => $query,
                'callback' => $callback,
                'softDelete' => static::usesSoftDelete() && config('scout.soft_delete', false)
            ]
        );
    }

    /**
     * Make all instances of the model searchable.
     *
     * @return void
     */
    public static function makeAllSearchable(): void
    {
        $self = new static;

        $softDelete = static::usesSoftDelete() && config('scout.soft_delete', false);

        $self->newQuery()
            ->when($softDelete, function ($query) {
                $query->withTrashed();
            })
            ->orderBy($self->getKeyName())
            ->searchable();
    }

    /**
     * Make the given model instance searchable.
     */
    public function searchable(): void
    {
        $this->model->newCollection([$this])->searchable();
    }

    public static function removeAllFromSearch()
    {
        $self = new static;

        $self->searchableUsing()->flush($self);
    }

    public function unsearchable(): void
    {
        $this->model->newCollection([$this->model])->unsearchable();
    }

    public function getScoutModelsByIds(Builder $builder, array $ids)
    {
        $query = static::usesSoftDelete() ? $this->model->withTrashed() : $this->model->newQuery();

        if ($builder->queryCallback) {
            call_user_func($builder->queryCallback, $query);
        }

        return $query
            ->whereIn($this->model->getScoutKeyName(), $ids)
            ->get();
    }

    public static function enableSearchSyncing(): void
    {
        ModelObserver::enableSyncingFor(get_called_class());
    }

    public static function disableSearchSyncing(): void
    {
        ModelObserver::disableSyncingFor(get_called_class());
    }

    /**
     * Temporarily disable search syncing for the given callback.
     *
     * @param $callback
     * @return mixed
     */
    public static function withoutSyncingToSearch($callback)
    {
        static::disableSearchSyncing();

        try {
            return $callback();
        } finally {
            static::enableSearchSyncing();
        }
    }

    /**
     * Get the index name for the model.
     *
     * @return string
     */
    public function searchableAs(): string
    {
        return config('scout.prefix').$this->model->getTable();
    }

    /**
     * Get the index-able data array for the model.
     *
     * @return array
     */
    public function toSearchableArray(): array
    {
        return $this->model->setVisible([
            'name',
            'code',
            'preview_text',
            'description',
            'search_synonym',
            'search_content',
        ])->toArray();
    }

    public function searchableUsing()
    {
        return app(EngineManager::class)->engine();
    }

    /**
     * Get the queue connection that should be used when syncing.
     *
     * @return string
     */
    public function syncWithSearchUsing(): string
    {
        return config('scout.queue.connection') ?: config('queue.default');
    }

    /**
     * Get the queue that should be used with syncing
     *
     * @return string
     */
    public function syncWithSearchUsingQueue()
    {
        return config('scout.queue.queue');
    }

    /**
     * Sync the soft deleted status for this model into the metadata.
     */
    public function pushSoftDeleteMetadata()
    {
        return $this->model->withScoutMetadata('__soft_deleted', $this->model->trashed() ? 1 : 0);
    }

    /**
     * Get all scout related metadata.
     *
     * @return array
     */
    public function scoutMetadata(): array
    {
        return $this->scoutMetadata;
    }

    /**
     * Set a scout related metadata.
     *
     * @param string $key
     * @param $value
     * @return \Lovata\Shopaholic\Models\Product|SearchScoutModel
     */
    public function withScoutMetadata(string $key, $value)
    {
        $this->scoutMetadata[$key] = $value;

        return $this->model;
    }

    /**
     * Get the value used to index the model.
     *
     * @return mixed
     */
    public function getScoutKey()
    {
        return $this->model->getKey();
    }

    /**
     * Get the key name used to index the model.
     *
     * @return mixed
     */
    public function getScoutKeyName()
    {
        return $this->getQualifiedKeyName();
    }

    /**
     * Determine if the current class should use soft deletes with searching.
     *
     * @return bool
     */
    protected static function usesSoftDelete(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(get_called_class()));
    }
}
