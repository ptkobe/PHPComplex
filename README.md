# PHPComplex

Complex vector and polar definitions with "branches".

Elementary complex numbers operations (add, sub, mult, div), and:
 `$a->Re()`, `$a->Im()`, `$a->arg()`, `$a->theta()`, `$a->abs()`, `$a->conj()`, `$a->inv()`, `$a->log()`, `$a->exp()`, `$a->pow($z)`, `$a->sqrt()`.

Namespace: `enove\PHPComplex`.

Release 0.1 (for PHP 5)

## Quick Start

### include '*Complex.php*':

```php
require_once '<path>/Complex.php';
```
or use an autoloader.

### set namespace use:

```php
use enove\PHPComplex\Complex;
```

### create a complex:

```php
 $w = new Complex(3, 4);
``` 
or:

```php
 $w = Complex::c_polar(1, pi()/3);
```
or:

```php
 $w = Complex::c_upolar(1, 1/6);
```
or, if you include `c_functions.php`:

```php
 $w = c_polar(1, pi()/3);
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

## To do

phpDoc documentation (75%)

Test Unit (25%)


