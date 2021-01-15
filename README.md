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
