# PostFilter

lets you modify the returned data from the underlying filter

[Tests](./../../tests/spec/Filter/PostFilterSpec.php)

## Usage

```xml
<doc>
    <foo>1337</foo>
</doc>
```

```php

$cb = function($value) {
    return 'foo_'.$value;
};

$data = $filter->find($xml, [
    'callable'    => cb,
    'real_filter' => '//foo',
]);

$data === 'foo_1337'
```

## Options

| Option | Required | Description | Allowed Values | Default |
|--------|----------|-------------|----------------|---------|
| `callable` | yes | the callable for modifying the data | callable | |
| `real_filter` | yes | the underlying filter | `string`,`array` |  |
| `condition` | no | filter values based on callback | callable | `false` |
| `validate` | no | validate the result | callable | `false` |
