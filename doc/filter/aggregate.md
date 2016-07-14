# AggregateFilter

this Filter lets you aggregate several other Filters into an associative Array

[Tests](./../../tests/spec/Filter/AggregateFilterSpec.php)

## Usage

### simple Scalars

```xml
<doc>
    <id>1</id>
    <color>red</color>
</doc>
```

```php
$data = $filter->find($xml, [
    'mappings' => [
        'id' => ['path' => '//id', 'cast' => 'int'], //optionally modify the underlying ScalarFilter
        'color' => '//color'
    ]
]);

$data === ['id' => 1337,'color' => 'red']
```

### complex Filter

```xml
<doc>
    <name>product</name>
    <primary>0</primary>
</doc>
```

```php
$data = $filter->find($xml, [
    'mappings' => [
        'items' => [
            'filter' => AggregateFilter::class,
            'mappings' => [
                'primary' => ['path' => '//primary', 'cast' => 'bool'],
                'name' => '//name'
            ]
        ]
    ]
]);

$data ===  [
    'items' => [
        'name' => 'product',
        'primary' => false
    ]
]
```

## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `mappings` | yes | the key=>value mappings | array | |
| `sort` | no | sort the result (only useful if `multiple` values allowed) | bool | `true` |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
