<?php

declare(strict_types = 1);

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\ActionCollection;
use Laravel\Nova\Fields\FieldCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\TrashedStatus;

trait CustomRelationshipFieldTrait
{
    public static function label(): string
    {
        return resolve(NovaRequest::class)->input('customRelationshipFieldLabel') ?? parent::label();
    }

    public function buildAvailableFields(NovaRequest $request, array $methods): FieldCollection
    {
        $method = 'fields';

        if ($attribute = $request->input('customRelationshipFieldAttribute')) {
            $method = "{$attribute}Fields";
            $methods = [ $method ];
        }

        $fields = collect([
            method_exists($this, $method) ? $this->$method($request) : [],
        ]);

        collect($methods)
            ->filter(function (string $method) {
                return $method != 'fields' && method_exists($this, $method);
            })
            ->each(function (string $method) use ($request, $fields) {
                $fields->push([ $this->{$method}($request) ]);
            });

        return FieldCollection::make(array_values($this->filter($fields->flatten()->all())));
    }

    public function resolveActions(NovaRequest $request): Collection
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Actions";

            if (method_exists($this, $method)) {

                return ActionCollection::make(
                    $this->filter($this->$method($request))
                );

            }

        }

        return parent::resolveActions($request);
    }

    public function resolveCards(NovaRequest $request): Collection
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Cards";

            if (method_exists($this, $method)) {
                return collect(array_values($this->filter($this->$method($request))));
            }

        }

        return parent::resolveCards($request);
    }

    public function resolveFilters(NovaRequest $request): Collection
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
