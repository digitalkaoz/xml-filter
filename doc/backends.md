# Backends

## `\SimpleXMLElement`

```php
$filter = $container['xml_filter'];
/* @var \Rs\XmlFilter\XmlFilter $filter */

$doc = $filter::load('<doc><foo>bar</foo></doc>');
```

## `DomNode`

```php
$filter = $container['xml_filter'];
/* @var \Rs\XmlFilter\XmlFilter $filter */

$doc = $filter::load('<doc><foo>bar</foo></doc>', \Rs\XmlFilter\Document\DomDocument::class);
```

## Own Backend

simple create a Class which implements `\Rs\XmlFilter\Document\Document` and use it

```php

$filter = $container['xml_filter'];
/* @var \Rs\XmlFilter\XmlFilter $filter */

$doc = $filter::load('<doc><foo>bar</foo></doc>', MyDocument::class);
```
