<?php

declare(strict_types=1);

namespace DigitalCreative\CustomRelationshipField;

use Laravel\Nova\Fields\HasMany;

class CustomRelationshipField extends HasMany
{
    public $component = 'custom-relationship-field';
}
