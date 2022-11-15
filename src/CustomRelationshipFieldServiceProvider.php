<?php

declare(strict_types = 1);

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CustomRelationshipFieldServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Nova::serving(static function (ServingNova $event) {
            Nova::script('custom-relationship-field', __DIR__ . '/../dist/js/field.js');
        });
    }
}
