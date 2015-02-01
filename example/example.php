<?php
namespace foo;

use enove\PHPComplex\Complex;
require_once '../Complex.php';

#require_once '../c_functions.php';

$class = PHPCOMPLEX_CLASS;

$w = new $class(3, 4, 2);
$z = new $class(-1/3, 0.1);

echo 'Complex exponents with complex bases example:',"\n";

echo 'v = w ** z:',"\n";
echo 'w = ',$w,"\n";
echo 'z = ',$z,"\n";
echo "\n";

$v = $w->pow($z);
echo 'v = ',$v,"\n";
echo "\n";

echo 'VERIFY:',"\n";
echo 'w2 = w = v ** (1/z):',"\n";

$zinv = $z->inv();
echo '1/z = ',$zinv,"\n";
$w2 = $v->pow($zinv);
echo 'w2 = ',$w2,"\n";
echo "\n";
echo "\n";


$n = $v->get_s();
echo 'Now, for all the results av = { v = w ** z } on default branch '.$n.':',"\n";

$av = $w->apow($z, $n);
echo 'av = { ',"\n";
foreach ($av as $va) {
	echo ' ',$va,"\n";
}
echo '}',"\n";

echo 'Note 1: only if |Re(z)| < 1 there\'s more than one result for apow().',"\n";
echo "\n";
echo "\n";


$n = 0;
echo 'And for all the results av = { v = w ** z } on branch '.$n.':',"\n";;

$av = $w->apow($z, $n);
echo 'av = { ',"\n";
foreach ($av as $va) {
	echo ' ',$va,"\n";
}
echo '}',"\n";

echo 'Note 2: In some circumstances there will be no results for the chosen branch.',"\n";
echo '        Then, the next available result for the following branches will be shown.',"\n";
echo "\n";
echo "\n";


echo 'Let\'s end with a simple multiplication:',"\n";;
$a = new Complex(3, 4);
$b = Complex::polar(1, 2*pi()/3);
echo 'a = ',$a,"\n";
echo 'b = ',$b,"\n";

echo 'y = a * b:',"\n";
$y = Complex::c_mult($a, $b);
echo 'y = ',$y, "\n";

echo "\n";
?>
