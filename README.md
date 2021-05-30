# Custom Relationship Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![License](https://img.shields.io/packagist/l/digital-creative/custom-relationship-field)](https://github.com/dcasia/custom-relationship-field/blob/master/LICENSE)

![Laravel Nova Custom Relationship Field in action](https://raw.githubusercontent.com/dcasia/custom-relationship-field/master/screenshots/demo.png)

This field works just like as the default HasMany relationship field from nova but **without requiring a real relation** with the resource.

That means you are free to show resource `A` into the details page of resource `B` without they having a real bound though the standard relationship in laravel.

# Installation

You can install the package via composer:

```
composer require digital-creative/custom-relationship-field
```

1. Add the `CustomRelationshipFieldTrait` to the resource (`A`) that will be related to, and implement the required: (instance) `relationFields`, (static) `relationQuery` methods and optional: `relationActions`, `relationFilters` instance methods on it, where `relation` is the name you choose for your custom relation.
2. Next, add the `CustomRelationshipField` to any resources (`B`) that you want the custom relation to be listed on the details page of.  
Example: `CustomRelationshipField::make('Relation Label', 'relation', RelatedResourceA::class)` where `relation` is the name you choose for your custom relation. Make sure you use the same relation name passed to the field to name the methods you implemented on the related resource in the first step. `RelatedResourceA` is the Nova resource class you are relating to which has the `CustomRelationshipFieldTrait`.

In the example below, the related resource is the same one that is being related to.

```php

use DigitalCreative\CustomRelationshipField\CustomRelationshipField;
use DigitalCreative\CustomRelationshipField\CustomRelationshipFieldTrait;

class Client extends Resource
{
    
    use CustomRelationshipFieldTrait;
   
    public function fields()
    {
        return [
            Text::make('Name')->rules('required'),
            // ...
            CustomRelationshipField::make('Clients with similar name', 'similarName', self::class),
        ];
    }

    public function similarNameFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name'),
            Text::make('Age'),
            //...
        ];
    }

    public static function similarNameQuery(NovaRequest $request, $query, Model $model)
    { 
        return $query->where('name', 'SOUNDS LIKE', $model->name)->whereKeyNot($model->getKey());
    }
    
    public function similarNameActions(NovaRequest $request) 
    {
        return [];
    }

    public function similarNameFilters(NovaRequest $request)
    {
        return [];
    }

}
```



## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/custom-relationship-field/master/LICENSE) for more information.
