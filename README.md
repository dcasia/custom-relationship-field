# Custom Relationship Field

[![Latest Version on Packagist](https://img.shields.io/packagist/v/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![Total Downloads](https://img.shields.io/packagist/dt/digital-creative/custom-relationship-field)](https://packagist.org/packages/digital-creative/custom-relationship-field)
[![License](https://img.shields.io/packagist/l/digital-creative/custom-relationship-field)](https://github.com/dcasia/custom-relationship-field/blob/master/LICENSE)

<picture>
  <source media="(prefers-color-scheme: dark)" srcset="https://raw.githubusercontent.com/dcasia/custom-relationship-field/main/screenshots/dark.png">
  <img alt="Custom Relationship Field in action" src="https://raw.githubusercontent.com/dcasia/custom-relationship-field/main/screenshots/light.png">
</picture>

This field works just like as the default HasMany relationship field from nova but **without requiring a real relation** with the resource.

That means you are free to show resource `A` into the details page of resource `B` without having to create a real relation between them.

# Installation

You can install the package via composer:

```shell
composer require digital-creative/custom-relationship-field
```

```php
use DigitalCreative\CustomRelationshipField\CustomRelationshipField;
use DigitalCreative\CustomRelationshipField\CustomRelationshipFieldTrait;

trait UserWithSimilarNameTrait
{    
    public static function similarNameQuery(NovaRequest $request, Builder $query, User $model): Builder
    { 
        return $query->where('last_name', $model->last_name)->whereKeyNot($model->getKey());
    }
    
    public function similarNameFields(NovaRequest $request): array
    {
        return [
            ID::make(),
            Text::make('First Name'),
            Text::make('Last Name'),
        ];
    }
    
    public function similarNameActions(NovaRequest $request): array 
    {
        return [];
    }

    public function similarNameFilters(NovaRequest $request): array
    {
        return [];
    }
}

class User extends Resource
{    
    use CustomRelationshipFieldTrait;
    use UserWithSimilarNameTrait;
    
    public function fields(NovaRequest $request): array
    {
        return [
            ...
            CustomRelationshipField::make('Users with similar name', 'similarName', User::class),
            ...
        ];
    }
}
```

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/custom-relationship-field/master/LICENSE) for more information.
