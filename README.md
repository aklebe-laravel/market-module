## Market Module

A module for [Mercy Scaffold Application](https://github.com/aklebe-laravel/mercy-scaffold.git)
(or any based on it like [Jumble Sale](https://github.com/aklebe-laravel/jumble-sale.git)).

This module required the module [WebsiteBase] and provides several features to make your website to an lightweight
webshop.

Including the following features:

1) Products
2) Categories
3) Payment Methods
4) Shipping Methods
5) Shopping Cart
6) Offers (like Orders, but just offers)
7) Multi Mandant, (every customer can be a trader)
8) Product-Ratings

## Console Commands

```php artisan market:products {product_command?} {--ids=}```

**product_command**: Delete product. All media items will also be removed if there is no other relation left.

**ids**: Comma separated ids. Also, a range (x-y) is accepted. Every id will be checked before deleting. 

### Examples

Delete all products from id 200 to 999999

```
php artisan market:products delete --ids="200-999999"
```

Delete products 1,2,4,9 and ids 10-20

```
php artisan market:products delete --ids="1,2,9,10-20,4"
```

Delete products below 100

```
php artisan market:products delete --ids="-99"
```

Delete products above 100

```
php artisan market:products delete --ids="101-"
```

## Extended Auto Import

```php artisan deploy-env:auto-import``` is extended to import products, categories and users

All (optional) columns not explicit exists will not be touched on update and set to default on create.

Every column not listed will not be ignored.

### Examples

Import all files containing "test", but processing products only.

```
php artisan deploy-env:auto-import "test" --type=product
```

### Product Import format

For auto import csv files must be start with ```product-``` or ```products-```.

A product will find in the following order: ```id``` or ```sku```

| Column        | Value description                                                                                                                                  |
|---------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| id and/or sku | to determine the product. If no product found, it wil be created.                                                                                  |
| is_enabled    | 0: can not be accessed                                                                                                                             |
| is_public     | 0: will not be listed                                                                                                                              |
| is_test       | 1: marked as test product                                                                                                                          |
| is_individual | 1: can only be sold once                                                                                                                           |
| user          | User id as number or as email. If user not found, product will be skipped.                                                                         |
| store         | Store id as number or as store code.                                                                                                               |
| sku           | Product sku.                                                                                                                                       |
| name          | Product name.                                                                                                                                      |
| images        | Comma separated image urls. Import will remember the import url and will avoid download/upload it again. Detached media items will not be deleted. |
| categories    | Comma separated categories (ids, or codes). Previous categories not listed here will be detached.                                                  |

Media items: If a media item with the same url in 'images' was found by this user, the existing media item will be used instead of create a new one.   

### Category Import format

For auto import csv files must be start with ```category-``` or ```categories-```.

A category will find in the following order: ```id``` or ```code```

| Column     | Value description                                                                                                                                  |
|------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| id         | category id                                                                                                                                        |
| is_enabled | 0: can not be accessed                                                                                                                             |
| is_public  | 0: will not be listed                                                                                                                              |
| code       | unique category code                                                                                                                               |
| name       | category name                                                                                                                                      |
| store      | Store id as number or as store code.                                                                                                               |
| images     | Comma separated image urls. Import will remember the import url and will avoid download/upload it again. Detached media items will not be deleted. |

### User Import format

For auto import csv files must be start with ```user-``` or ```users-```.

A user will find in the following order: ```id```, ```email```, ```name```. If nothing of them is given, no user will be
created/updated.

| Column     | Value description                                                                                                                                  |
|------------|----------------------------------------------------------------------------------------------------------------------------------------------------|
| id         | user id                                                                                                                                            |
| email      | user email                                                                                                                                         |
| name       | user name                                                                                                                                          |
| password   | user password                                                                                                                                      |
| is_enabled | 0 or 1                                                                                                                                             |
| acl_groups | Comma separated ACL user group.                                                                                                                    |
| images     | Comma separated image urls. Import will remember the import url and will avoid download/upload it again. Detached media items will not be deleted. |

### Address Import format

### AclGroup Import format

### Navigation Import format
