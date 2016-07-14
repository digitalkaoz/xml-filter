# ScalarFilter

the most basic Filter, lets you find scalar values in an document

[Tests](./../../tests/spec/Filter/ScalarFilterSpec.php)

## Usage

### Multiple

```xml
<doc>
    <foo>10</foo>
    <foo>20</foo>
</doc>
```

```php
$data = $filter->find($xml, [
    'path' => '/foo',
    'cast' => 'int',
    'multiple' => true
]);

$data === [10,20]
```

### Sort by Xpath

```xml
<doc>
    <foo>bar</foo>
    <sort><what>bar</what><value>2</value></sort>
    <foo>foo</foo>
    <sort><what>foo</what><value>1</value></sort>
</doc>
```

```php
$data = $filter->find($xml, [
    'path' => '/doc/foo',
    'multiple' => true,
    'sort' => '/doc/sort[./what=./text()]/value'
]);

$data === ['foo', 'bar']
```

## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `path` | yes | the Xpath to resolve | string | |
| `multiple` | no | allow multiple values | bool | `false` |
| `cast` | no | type cast the value(s) | int,float,string,bool | `'string'` |
| `sort` | no | sort the result (only useful if `multiple` values allowed) | `bool` `string` | `true` |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
