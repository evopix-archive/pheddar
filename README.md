# Pheddar - Transform human readable text into integers or floats.

Pheddar is a port of the Ruby library [Cheddar](https://github.com/kparnell/cheddar).

## Examples

The basics.

```php
<?php

use Pheddar\Human;

Human::to_number('15 hundred');
// => 1500

Human::to_number('$200 million');
// => 200000000

Human::to_number('15.72 vigintillion');
// => 1.5720000000000002E+64
```

Slightly more advanced.

```php
<?php

Human::to_number('25 bucks');
// => 25

Human::to_number('20 large');
// => 2000

Human::to_number('33 straight up large bills');
// => 3300
```