# XmlFilter

this library lets you filter complex Data-Structures out of XML Documents into some Array Structure (nested Arrays, Maps, Strings ...).
* It is capable of using different XML Backends (`\SimpleXml*` or `\Dom*` or even your Own)
* It has Support for Type-Casting, Sorting, Validation, Reference-Checking, Conditional-Inclusion, Post-Processing, Merging, Aggregating and ...
* It is extendable (it uses Pimple behind the curtain), so you can provide your own Filter, or override nearly every part

## Installation

```
$ composer install
```

## Example

> Given I Have the following XML Document

```xml
<doc>
    <foo>foo</foo>
    <bar>20</bar>
    <bar>30</bar>
    <bar>10</bar>
</doc>
```

> When I use the following Configuration (while using the `Yaml` Loader)

```yml
Rs\XmlFilter\Filter\AggregateFilter:
    mappings:
        bazz:
            filter: Rs\XmlFilter\Filter\AggregateFilter
            mappings:
                foo: "/doc/foo"
        bar:
            path: "/doc/bar"
            cast: "int"
            sort: true
            multiple: true
```

> I want to get the following Array after filtering

```php
[
    'bazz' => [
        'foo' => 'foo',
    ],
    'bar' => [10, 20, 30]
]
```

## Usage

* [Filters](./doc/filters.md)
* [Loaders](./doc/loaders.md)
* [Backends](./doc/backends.md)

## PHAR

to build a phar simply run

```bash
$ composer build
```

## Tests

```
$ composer test-all
```
