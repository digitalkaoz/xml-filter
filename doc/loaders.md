# Loaders

## `ArrayLoader`

```php
$loader = new \Rs\XmlFilter\Loader\ArrayLoader([
   \Rs\XmlFilter\Filter\ScalarFilter::class => ['path' => '/doc/foo'],
]);

$result = $filter->filter($doc, $loader);
```

## `YamlLoader`

```php
$loader = new \Rs\XmlFilter\Loader\YamlLoader(__DIR__ . '/config.yaml');

$result = $filter->filter($doc, $loader);
```

## `JsonLoader`

```php
$loader = new \Rs\XmlFilter\Loader\JsonLoader(__DIR__ . '/config.json');

$result = $filter->filter($doc, $loader);
```

## Own

simply implement `Rs\XmlFilter\Loader\Loader`

```php
$loader = new MarkdownLoader(__DIR__ . '/config.md');

$result = $filter->filter($doc, $loader);
```
