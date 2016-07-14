# ReferenceFilter

lets you check for the existence of some xpath, throws an Exception in case its missing
its distinction from a `not nullable` ScalarFilter is the ability to pass another Xpath as Error Element

[Tests](./../../tests/spec/Filter/ReferenceFilterSpec.php)

## Usage

### Simplest Use-Case

```xml
<doc>
    <foo>1337</foo>
</doc>
```

```php
$data = $filter->find($xml, [
    'reference' => '//foo',
]);

$data === '1337'
```

### with a Custom Error Element

```xml
<doc>
    <bar>1337</foo>
</doc>
```

```php
$data = $filter->find($xml, [
    'reference' => '//foo',
    'element'   => '//bar'
]);

//Exception with, the error Element would from '/bar'
```

### with a more complex value

```xml
<doc>
    <foo>1337</foo>
    <bar>lol</bar>
</doc>
```

```php
$data = $filter->find($xml, [
    'reference' => '//foo',
    'value'   => [
        'filter' => AggregateFilter::class,
        'mappings' => [
            'foo' => '//foo',
            'bar' => '//bar'
        ]
    ]    
]);

$data === ['foo' => '1337', 'bar' => 'lol']
```


## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `reference` | yes | the xpath to check for existence | string | |
| `element` | no | the error element | string | `null` |
| `value` | yes | the path for the value *defaults to the reference* | `string, array,null` | |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
