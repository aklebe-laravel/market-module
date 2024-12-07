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

```php artisan market:manage {subject} {sub_command?} {--ids=} {--since-created=} {--last-seed}```

**subject**: Can be a model like ```model-product``` or ```model-user```. 

**sub_command**: one of these: ```info,status,repair,delete```. In case of delete all media items will also be removed if there are no linked objects left (happens in event/listener). Use status to check what's going on.

**ids** (optional): Comma separated ids to filter results. Also, a range (x-y) is accepted. Every id will be checked before deleting.

**since_created** (optional): Timestamp like "1973-03-27 17:00" to filter results.

### Examples

Delete all products from id 200 to 999999

```
php artisan market:manage model-product delete --ids="200-999999"
```

Delete products 1,2,4,9 and ids 10-20

```
php artisan market:manage model-product delete --ids="1,2,9,10-20,4"
```

Delete products below 100

```
php artisan market:manage model-product delete --ids="-99"
```

Delete products above 100

```
php artisan market:manage model-product delete --ids="101-"
```

Show info for all Users created since 2073-03-27 17:00
```
php artisan market:manage model-user info --since-created="2073-03-27 17:00"
```

Show info for all Models created since 2073-03-27 17:00
```
php artisan market:manage model-* info --since-created="2073-03-27 17:00"
```

Delete all Models created since 2073-03-27 17:00
```
php artisan market:manage model-* delete --since-created="2073-03-27 17:00"
```


## Extended Auto Import

```php artisan deploy-env:auto-import``` is extended to import products, categories and users.
The default location of import files (if not explicit given in ```--root```) is ```storage/app/import```.
Specify ```--dir``` if you want select a specific sub path in root.

Use ```market:products delete``` to delete your last imports.

All (optional) source columns not explicit exists will not be touched on update and will be set to default on create.

Every column not listed below is matter and will not be ignored.

### Examples

Import all files containing "test", but processing products only. 
This also means in this case the files must have product data. 
Every file and row will be processed, but if no product data were found, nothing happens.

```
php artisan deploy-env:auto-import "test" --type=product
```

if no type option given, the files must have the format ```"[type]-[...]"```.
So found files named ```"product-test-01.csv"``` and ```"product-my-big-test.csv"``` will be processed as products with:

```
php artisan deploy-env:auto-import "test"
```

Found files named ```"category-test-99.csv"``` and ```"categories-in-computer.csv"``` will be processed as categories with the same:

```
php artisan deploy-env:auto-import "test"
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
