# PHPComplex

Complex Vector and polar definitions with "branches".

Elementary complex numbers operations, plus `$a->log()`, `$a->exp()` and `$a->pow($z)`.

Namespace: enove\PHPComplex

(for PHP 5)

## Usage

Include '*Complex.php*':
require_once '<path>/Complex.php';

Set namespace:
use enove\PHPComplex\Complex;

create a complex:
 $w = new Complex(3, 4);
or
 $w = Complex::polar(1, pi()/3);

print a complex:
 echo $w;
 
## More
Optionally include '*c_functions.php*' if you want the procedural style functions.

See **iComplex** interface (*iComplex.php*)

See **PHPUnit** test (*tests/PHPComplexTest.php*)

Optionally create your own class extending **Complex** and set `PHPCOMPLEX_CLASS` accordingly.

## To do

phpDoc documentation



