# PHPComplex

Complex Vector and polar definitions with "branches".

Elementary complex numbers operations (add, sub, mult, div), and:
 `$a->Re()`, `$a->Im()`, `$a->arg()`, `$a->theta()`, `$a->abs()`, `$a->conj()`, `$a->inv()`, `$a->log()`, `$a->exp()`, `$a->pow($z)`, `$a->sqrt()`.

Namespace: `enove\PHPComplex`.

(for PHP 5)

## Quick Start

### include '*Complex.php*':

```php
require_once '<path>/Complex.php';
```

### set namespace:

```php
use enove\PHPComplex\Complex;
```

### create a complex:

```php
 $w = new Complex(3, 4);
``` 
or

```php
 $w = Complex::polar(1, pi()/3);
```

### print a complex:

```php
 echo $w;
```
 
## More
See **Example** (`example/example.php`).

Optionally include `c_functions.php` if you want the procedural style functions.

See **iComplex** interface (`iComplex.php`).

See **PHPUnit** test (`tests/PHPComplexTest.php`).

Optionally create your own class extending **Complex** and set `PHPCOMPLEX_CLASS` accordingly.

## To do

phpDoc documentation (50%)



