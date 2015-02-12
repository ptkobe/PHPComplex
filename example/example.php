<?php
namespace foo;
use enove\PHPComplex\Complex;

require_once __DIR__.'/../autoloader.php';

$w = new Complex(0, 4, 3);
	#$w = Complex::c_coords(0, 4, 3);
	#$w = Complex::c_polar(4, 3*2*pi()+1/2*pi());
	#$w = Complex::c_upolar(4, 3.25);
$z = new Complex(-1/3, 0.1);

echo 'Complex exponents with complex bases example: v = w ** z',"\n";
echo 'w = ',$w,"\n";
echo 'z = ',$z,"\n";

$v = $w->pow($z);
echo 'v = ',$v,"\n";
echo "\n";

echo 'VERIFY w = v ** (1/z) = w2:',"\n";

$zinv = $z->inv();
echo '1/z = ',$zinv,"\n";
$w2 = $v->pow($zinv);
echo 'w2 =  ',$w2,"\n";
echo "\n";
echo "\n";


$n = $v->branch();
echo 'Now, for all the results av = { v = w ** z } on default branch '.$n.':',"\n";

$av = $w->apow($z, $n);
echo 'av = { ',"\n";
foreach ($av as $va) {
	echo '   ',$va,"\n";
}
echo '}',"\n";

echo 'Note 1: only if |Re(z)| < 1 it may be more than one result for apow().',"\n";
echo "\n";
echo "\n";


$n++;
echo 'And for all the results av = { v = w ** z } on branch, say, '.$n.':',"\n";;

$av = $w->apow($z, $n);
echo 'av = { ',"\n";
foreach ($av as $va) {
	echo '   ',$va,"\n";
}
echo '}',"\n";
echo 'Note 2: In certain circumstances there will be no results for the chosen branch.',"\n";
echo "\n";
echo "\n";


echo 'Let\'s end by changing the output format, and a simple multiplication:',"\n";;

$a = new Complex(0, 4);
$b = Complex::c_upolar(2, 1/3);
$y = $a->mult($b);

// Define a callable format
$f = function ($a) { 
	return sprintf( '%3$.5g < %6$+.2fº(%5$d)'
		, $a->Re(), $a->Im(), $a->abs(), $a->arg(), $a->branch(), $a->uarg()*360
	); 
};
$old_format = Complex::set_format($f);

echo 'a = ',$a,"\n";
echo 'b = ',$b,"\n";

echo 'y = a * b:',"\n";
echo 'y = ',$y, "\n";

// Restore the previous format
Complex::set_format($old_format);

echo "\n";
?>
