# MapFilter

creates a key value map of different searches

[Tests](./../../tests/spec/Filter/MapFilterSpec.php)

## Usage

### Simple Map

```xml
<doc>
    <foo>
        <key>c</key>
        <value>bazz</value>
    </foo>
    <foo>
        <key>a</key>
        <value>foo</value>
    </foo>
    <foo>
        <key>b</key>
        <value>bar</value>
    </foo>
</doc>
```

```php
$data = $filter->find($xml, [
    'basePath' => '/foo',
    'key' => './key',
    'value' => './value'
]);

$data === [
    'a' => 'foo',
    'b' => 'bar,
    'c' => 'bazz'
]
```

### Sort By Xpath

```xml
<doc>
    <foo>
        <key>1</key>
        <value>bazz</value>
    </foo>
    <foo>
        <key>2</key>
        <value>foo</value>
    </foo>
    <foo>
        <key>3</key>
        <value>bar</value>
    </foo>
</doc>
```

```php
$data = $filter->find($xml, [
    'basePath' => '/foo',
    'value' => './value',
    'sort' => './key',
]);

$data === [
    'bazz'
    'foo',
    'bar
]
```


## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `basePath` | yes | the basePath for keys and values | string | |
| `key` | no | the for the keys | string | |
| `value` | yes | the path for the values | string, array | |
| `sort` | no | sort the result by keys | `bool` `string` | `true` |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
