<?php

declare(strict_types = 1);

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\ActionCollection;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use Laravel\Nova\TrashedStatus;

/**
 * @mixin Resource
 */
trait CustomRelationshipFieldTrait
{
    public static function label(): string
    {
        return resolve(NovaRequest::class)->input('customRelationshipFieldLabel') ?? parent::label();
    }

    protected function fieldsMethod(NovaRequest $request): string
    {
        $prefix = 'fields';

        if ($attribute = $request->input('customRelationshipFieldAttribute')) {
            $prefix = "{$attribute}Fields";
        }

        if ($request->isInlineCreateRequest() && method_exists($this, "{$prefix}ForInlineCreate")) {
            return "{$prefix}ForInlineCreate";
        }

        if ($request->isResourceIndexRequest() && method_exists($this, "{$prefix}ForIndex")) {
            return "{$prefix}ForIndex";
        }

        if ($request->isResourceDetailRequest() && method_exists($this, "{$prefix}ForDetail")) {
            return "{$prefix}ForDetail";
        }

        if ($request->isCreateOrAttachRequest() && method_exists($this, "{$prefix}ForCreate")) {
            return "{$prefix}ForCreate";
        }

        if ($request->isUpdateOrUpdateAttachedRequest() && method_exists($this, "{$prefix}ForUpdate")) {
            return "{$prefix}ForUpdate";
        }

        return $prefix;
    }

    public function resolveActions(NovaRequest $request): Collection
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Actions";

            if (method_exists($this, $method)) {

                return ActionCollection::make(
                    $this->filter($this->{$method}($request)),
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
                return collect(array_values($this->filter($this->{$method}($request))));
            }

        }

        return parent::resolveCards($request);
    }

    public function resolveFilters(NovaRequest $request): Collection
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Filters";

            if (method_exists($this, $method)) {
                return collect(array_values($this->filter($this->{$method}($request))));
            }

        }

        return parent::resolveFilters($request);
    }

    public static function buildIndexQuery(
        NovaRequest $request,
        $query,
        $search = null,
        array $filters = [],
        array $orderings = [],
        $withTrashed = TrashedStatus::DEFAULT,
    ): Builder
    {
        if ($method = $request->input('customRelationshipFieldAttribute')) {

            $method = "{$method}Query";

            if (method_exists(static::class, $method)) {

                return static::applyOrderings(static::applyFilters(
                    $request, static::initializeQuery($request, $query, $search, $withTrashed), $filters,
                ), $orderings)->tap(function ($query) use ($method, $request): void {

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
