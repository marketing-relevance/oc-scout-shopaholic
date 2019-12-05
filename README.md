# Laravel Scout for Shopaholic plugin
Shopaholic eCommerce extension: allows to search products, categories, tags, brands using laravel Scout.

## Description
[Laravel Scout for Shopaholic](https://github.com/mrelevance/oc-scout-shopaholic) plugin adds fields **'search_synonym'**, **'search_content'** 
to [Product](https://github.com/lovata/oc-shopaholic-plugin/wiki/ProductModel), 
[Brand](https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandModel), 
[Category](https://github.com/lovata/oc-shopaholic-plugin/wiki/CategoryModel), 
[Tag](https://github.com/lovata/oc-shopaholic-plugin/wiki/TagModel) models.

[Laravel Scout for Shopaholic](https://github.com/mrelevance/oc-scout-shopaholic) plugin adds `searchScout($sSearchString)` method to
**[ProductCollection](https://github.com/lovata/oc-shopaholic-plugin/wiki/ProductCollection)**,
**[BrandCollection](https://github.com/lovata/oc-shopaholic-plugin/wiki/BrandCollection)**,
**[CategoryCollection](https://github.com/lovata/oc-shopaholic-plugin/wiki/CategoryCollection)**,
**[TagCollection](https://github.com/lovata/oc-shopaholic-plugin/wiki/TagCollection)** classes.

```php
$obList =  ProductCollection::make()->search('test search');
```

## Installation Guide
After installing [Laravel Scout for Shopaholic](https://github.com/mrelevance/oc-scout-shopaholic) plugin, you should publish the Scout configuration using the vendor:publish Artisan command. This command will publish the scout.php configuration file to your config directory:
```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

### MySQL Driver
Search Shopaholic Models using MySQL FULLTEXT Indexes
> **Note:** Any models you plan to search using the driver must use a MySQL MyISAM or InnoDB table.

**Append the default configuration to config/scout.php**
```php
'mysql' => [
    'mode' => 'NATURAL_LANGUAGE',
    'model_directories' => [app_path()],
    'min_search_length' => 0,
    'min_fulltext_search_length' => 4,
    'min_fulltext_search_fallback' => 'LIKE',
    'query_expansion' => false
],
```

Set `SCOUT_DRIVER=mysql` in your .env file

Please note this Laravel Scout driver does not need to update any indexes when a Model is changed as this is handled natively by MySQL. Therefore you can safely disable queuing in config/scout.php.
```php
 /*
 |--------------------------------------------------------------------------
 | Queue Data Syncing
 |--------------------------------------------------------------------------
 |
 | This option allows you to control if the operations that sync your data
 | with your search engines are queued. When this is set to "true" then
 | all automatic data syncing will get queued for better performance.
 |
 */
 'queue' => false,
```

In addition there is no need to use the php artisan scout:import command. However, if you plan to use this driver in either NATURAL_LANGUAGE or BOOLEAN mode you should first run the provided console command to create the needed FULLTEXT indexes.

Full documentation of MySQL driver [here](https://github.com/yabhq/laravel-scout-mysql-driver)
