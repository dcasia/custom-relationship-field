# Custom Relationship Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![License](https://img.shields.io/packagist/l/digital-creative/custom-relationship-field)](https://github.com/dcasia/custom-relationship-field/blob/master/LICENSE)

This field works just like as the default HasMany relationship field from nova but **without requiring a real relation** with the resource.

That means you are free to show resource `A` into the details page of resource `B` without they having a real bound though the standard relationship in laravel.

# Installation

You can install the package via composer:

```
composer require digital-creative/custom-relationship-field
```

Next add the `CustomRelationshipField` to the resource you wanna display the relation.

```php
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

}
```

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/custom-relationship-field/master/LICENSE) for more information.
