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

## ⭐️ Show Your Support

Please give a ⭐️ if this project helped you!

### Other Packages You Might Like

- [Expandable Table Row](https://github.com/dcasia/expandable-table-row) - Provides an easy way to append extra data to each row of your resource tables.
- [Collapsible Resource Manager](https://github.com/dcasia/collapsible-resource-manager) - Provides an easy way to order and group your resources on the sidebar.
- [Resource Navigation Tab](https://github.com/dcasia/resource-navigation-tab) - Organize your resource fields into tabs.
- [Resource Navigation Link](https://github.com/dcasia/resource-navigation-link) - Create links to internal or external resources.
- [Nova Mega Filter](https://github.com/dcasia/nova-mega-filter) - Display all your filters in a card instead of a tiny dropdown!
- [Nova Pill Filter](https://github.com/dcasia/nova-pill-filter) - A Laravel Nova filter that renders into clickable pills.
- [Nova Slider Filter](https://github.com/dcasia/nova-slider-filter) - A Laravel Nova filter for picking range between a min/max value.
- [Nova Range Input Filter](https://github.com/dcasia/nova-range-input-filter) - A Laravel Nova range input filter.
- [Nova FilePond](https://github.com/dcasia/nova-filepond) - A Nova field for uploading File, Image and Video using Filepond.
- [Custom Relationship Field](https://github.com/dcasia/custom-relationship-field) - Emulate HasMany relationship without having a real relationship set between resources.
- [Column Toggler](https://github.com/dcasia/column-toggler) - A Laravel Nova package that allows you to hide/show columns in the index view.
- [Batch Edit Toolbar](https://github.com/dcasia/batch-edit-toolbar) - Allows you to update a single column of a resource all at once directly from the index page.

## License

The MIT License (MIT). Please see [License File](https://raw.githubusercontent.com/dcasia/custom-relationship-field/master/LICENSE) for more information.
