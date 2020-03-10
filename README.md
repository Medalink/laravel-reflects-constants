# laravel-reflects-constants
A small helper package that aids in the retrieval of class constants of models.

`composer require medalink/laravel-reflects-constants`

### How to use
Let's assume you have a class with defined constants for different types.

```php
<?php
class ProductInformation
{
    use \Medalink\Reflects\Constants;

    /**
    * Optional constant blacklist, anything in here will be filtered
    */
    public $reflectedConstantsBlacklist = [
        'TEST',
    ];

    const TYPE_OVERVIEW = 'OVERVIEW';
    const TYPE_SAFETY = 'SAFETY';
    const TYPE_WARRANTY = 'WARRANTY';
    const TYPE_PRODUCT_INFO = 'PRODUCT_INFO';
    const TEST = 'TEST';
}
```

This package will allow you to return these constants as a human readable array. This is most useful when using this data in various places through your application. Let's take a look at using this package to help a factory choose a random type.

```php
$factory->define(ProductInformation::class, function (Faker $faker) {
    return [
        'type' => $faker->randomElement(ProductInformation::getReflectedConstants('TYPE_')),
    ];
});
```

The resulting array would look like this:

```php
$types = ProductInformation::getReflectedConstants('TYPE_');

$types = [
    'Overview',
    'Safety',
    'Warranty',
    'Product Info'
];
```

Let's take a look at using this to populate a laravel nova options dropdown.

```php
Select::make('Type')
    ->options(ProductInformation::getReflectedConstants('TYPE_'))
    ->sortable(),
```

The `getReflectedConstants` supports a blacklist, a prefix, returning the prefix with the constant name, and a human readable toggle. Take a look at the source for more details on how to use these features.
