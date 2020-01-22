<?php

namespace DigitalCreative\CustomRelationshipField;

use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class CustomRelationshipFieldServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Nova::serving(static function (ServingNova $event) {
            Nova::script('custom-relationship-field', __DIR__ . '/../dist/js/field.js');
        });
    }
}
