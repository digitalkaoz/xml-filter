# MergeFilter

this Filter is useful for merging different Filters into one Array

[Tests](./../../tests/spec/Filter/MergeFilterSpec.php)

## Usage

```xml
<doc>
    <foo>foo</foo>
    <primary>1</primary>
    <name>bar</name>
</doc>
```

```php
$data = $filter->find($xml, [
    'filters' => [
        '//foo',
        [
            'filter' => AggregateFilter::class,
            'mappings' => [
                'primary' => ['path' => '//primary', 'cast' => 'bool'],
                'name' => '//name'
            ]
        ]
    ]
]);

$data === [
    'foo',
    ['primary' => true, 'name' => 'bar']
]
```

## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `filters` | yes | the merge filters | array | |
| `sort` | no | sort the result (only useful if `multiple` values allowed) | bool | `false` |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
