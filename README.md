# XmlFilter

this library lets you filter complex Data-Structures out of XML Documents into some Array Structure (nested Arrays, Maps, Strings ...).
* It is capable of using different XML Backends (`\SimpleXml*` or `\Dom*` or even your Own)
* It has Support for Type-Casting, Sorting, Validation, Reference-Checking, Conditional-Inclusion, Post-Processing, Merging, Aggregating and ...
* It is extendable (it uses Pimple behind the curtain), so you can provide your own Filter, or override nearly every part

[![Build Status](https://img.shields.io/travis/digitalkaoz/xml-filter/master.svg?style=flat-square)](https://travis-ci.org/digitalkaoz/xml-filter)
[![Dependency Status](https://www.versioneye.com/user/projects/5790de4099a405000d2d6ce8/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/5790de4099a405000d2d6ce8)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/digitalkaoz/xml-filter.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/xml-filter/?branch=master)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/digitalkaoz/xml-filter/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/digitalkaoz/xml-filter/?branch=master)
[![SensioLabsInsight](https://img.shields.io/sensiolabs/i/1d31f014-dbbd-4c41-a64d-a4e41cdfa3a3.svg?style=flat-square)](https://insight.sensiolabs.com/projects/1d31f014-dbbd-4c41-a64d-a4e41cdfa3a3)
[![Latest Stable Version](https://img.shields.io/packagist/v/digitalkaoz/xml-filter.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/xml-filter)
[![Total Downloads](https://img.shields.io/packagist/dt/digitalkaoz/xml-filter.svg?style=flat-square)](https://packagist.org/packages/digitalkaoz/xml-filter)
[![StyleCI](https://styleci.io/repos/63342748/shield)](https://styleci.io/repos/63342748)

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
