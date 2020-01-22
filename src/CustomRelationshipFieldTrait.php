<?php

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\TrashedStatus;

trait CustomRelationshipFieldTrait
{

    /**
     * @return string
     */
    public static function label()
    {
        return resolve(NovaRequest::class)->input('customRelationshipFieldLabel') ?? parent::label();
    }

    /**
     * Get the fields that are available for the given request.
     *
     * @param NovaRequest $request
     *
     * @return FieldCollection
     */
    public function availableFields(NovaRequest $request)
    {

        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Fields";

            if (method_exists($this, $method)) {

                return new FieldCollection(array_values($this->filter($this->$method($request))));

            }

        }

        return parent::availableFields($request);

    }

    /**
     * Get the actions for the given request.
     *
     * @param NovaRequest $request
     *
     * @return Collection
     */
    public function resolveActions(NovaRequest $request)
    {

        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Actions";

            if (method_exists($this, $method)) {

                return collect(array_values($this->filter($this->$method($request))));

            }

        }

        return parent::resolveActions($request);

    }

    /**
     * Get the filters for the given request.
     *
     * @param NovaRequest $request
     *
     * @return Collection
     */
    public function resolveFilters(NovaRequest $request)
    {

        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Filters";

            if (method_exists($this, $method)) {

                return collect(array_values($this->filter($this->$method($request))));

            }

        }

        return parent::resolveFilters($request);

    }

    /**
     * Build an "index" query for the given resource.
     *
     * @param NovaRequest $request
     * @param Builder $query
     * @param string $search
     * @param array $filters
     * @param array $orderings
     * @param string $withTrashed
     *
     * @return Builder
     */
    public static function buildIndexQuery(NovaRequest $request, $query, $search = null, array $filters = [], array $orderings = [], $withTrashed = TrashedStatus::DEFAULT)
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Query";

            if (method_exists(static::class, $method)) {

                return static::applyOrderings(static::applyFilters(
                    $request, static::initializeQuery($request, $query, $search, $withTrashed), $filters
                ), $orderings)->tap(static function ($query) use ($method, $request) {

                    $resource = Nova::modelInstanceForKey($request->viaResource)
                                    ->newQueryWithoutScopes()
                                    ->find($request->viaResourceId);

                    static::$method($request, $query->with(static::$with), $resource);

                });

            }

        }

        return parent::buildIndexQuery(...func_get_args());
    }
}
