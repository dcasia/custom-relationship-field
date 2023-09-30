<?php

declare(strict_types = 1);

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class CustomRelationshipFieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Nova::serving(static function (ServingNova $event): void {
            Nova::script('custom-relationship-field', __DIR__ . '/../dist/js/field.js');
        });

        $this->app->afterResolving(NovaRequest::class, function (NovaRequest $request): void {

            if ($relationshipType = $request->relationshipType) {

                if (Str::startsWith($relationshipType, 'CustomRelationshipField')) {

                    $encoded = Str::after($relationshipType, ':');

                    [ $attribute, $label ] = Str::of(base64_decode($encoded))->explode('|_::_|');

                    $request->merge([
                        'relationshipType' => 'hasMany',
                        'customRelationshipFieldAttribute' => $attribute,
                        'customRelationshipFieldLabel' => $label,
                    ]);

                }

            }

        });
    }
}
