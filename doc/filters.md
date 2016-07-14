# Filters

* [Scalar](./filter/scalar.md) finds simple Values
* [Aggregate](./filter/aggregate.md) can aggregate other filters
* [Merge](./filter/merge.md) merges a set of filters
* [Map](./filter/map.md) merges a set of filters
* [Reference](./filter/reference.md) lets you check for the existence of an xpath

## The Context

* `xpath` to a node value which can be used in in xpath expressions like `/foo[./bar="%s"]/bazz`
* if you dont provide a context, the context will be automaticly the one from the current Map Iteration, so all relative xpath expressions in nested filters will the this Node instead


> more Context examples!

## Own Filters

to register your own Filter simply attach it to to the `FilterManager`:

```php

class MyFilter implements \Rs\XmlFilter\Filter\Filter
{
      public function find(Element $element, array $options)
      {
          return 'foo';
      }

      public function getOptions(OptionsResolver $resolver)
      {
      }
}

/* @var \Rs\XmlFilter\XmlFilter $filter */
$filter->register('foo', new MyFilter());

//or as a callable to get access to the service container

$filter->register('foo', function(Container $pimple) {
    return new MyFilter()
});
```

use it as follows:

```php
$loader = new \Rs\XmlFilter\Loader\ArrayLoader([
   'foo' => [],
]);

$result = $filter->filter($doc, $loader);

$result === 'foo';
```
