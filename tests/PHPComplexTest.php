<?php
/**
* @package PHPComplex
*/
use enove\PHPComplex\Complex;

require_once __DIR__.'/../autoloader.php';

class PHPComplexTest extends PHPUnit_Framework_TestCase
{

/* new and polar */

	public function testNew ()
	{
		$a = new Complex();
		$t = new Complex(0,0,0);
		$this->assertEquals($t, $a);

		$a = new Complex(3);
		$t = new Complex(3,0,0);
		$this->assertEquals($t, $a);

		$a = new Complex(NULL,4);
		$t = new Complex(0,4,0);
		$this->assertEquals($t, $a);

		$a = new Complex(NULL,NULL,2);
		$t = new Complex(0,0,2);
		$this->assertEquals($t, $a);
		
		$a = new Complex(0, 0, 2);
		$this->assertEquals(0, $a->abs());
		$this->assertEquals(0, $a->arg());
		$this->assertEquals(0, $a->theta());

		$a = new Complex(3, 4, 2);
		$this->assertEquals(3, $a->Re());
		$this->assertEquals(4, $a->Im());
		$this->assertEquals(2, $a->branch());
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(atan(4/3), $a->arg());
		$this->assertEquals(2*2*pi()+atan(4/3), $a->theta());

		$a = new Complex(3, -4, 2);
		$this->assertEquals(-atan(4/3), $a->arg());

		$a = new Complex(-3, 4, 2);
		$this->assertEquals(pi()-atan(4/3), $a->arg());

		$a = new Complex(-3, -4, 2);
		$this->assertEquals(-pi()+atan(4/3), $a->arg());
	}

	public function testPolar ()
	{
		$this->assertEquals(pi(), atan2(0,-5));

		$a = Complex::c_polar(5, 2*2*pi()+atan(4/3));
		$this->assertEquals(3, $a->Re());
		$this->assertEquals(4, $a->Im());
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(atan(4/3), $a->arg());
		$this->assertEquals(2, $a->branch());
		$this->assertEquals(2*2*pi()+atan(4/3), $a->theta());

		$a = Complex::c_polar(0, 2*2*pi());
		$this->assertEquals(0, $a->abs());
		$this->assertEquals(0, $a->arg());
		$this->assertEquals(0, $a->theta());
		$this->assertEquals(0, $a->branch());

		$a = Complex::c_polar(5, 2*2*pi()-pi());
		#$a = Complex::c_polar(5, 3*pi());
		$this->assertEquals(-5, $a->Re());
		$this->assertEquals(0, $a->Im());
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(pi(), $a->arg());
		$this->assertEquals(1, $a->branch());
		$this->assertEquals(2*pi()*2-pi(), $a->theta());

		$a = Complex::c_polar(5);
		#$t = new Complex(5,0,0);
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(5, $a->Re());
		$this->assertEquals(0, $a->Im());
		$this->assertEquals(0, $a->arg());
		#$this->assertEquals($t, $a);
	}

/* branch */

	public function testMinusConjInvMinusBranch ()
	{
		
		$a = new Complex(-2,0);
		$this->assertEquals(pi(), $a->arg());
		$this->assertEquals(0, $a->branch());
		$this->assertEquals(-1, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(0, $b->branch());
		$b = $a->conj();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-1, $b->branch());
		$b = $a->inv();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-1, $b->branch());
		$this->assertEquals(0, $b->uminus_branch());

		$a = new Complex(2,0,3);
		$this->assertEquals(0, $a->arg());
		$this->assertEquals(3, $a->branch());
		$this->assertEquals(-3, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(3, $b->branch());
		$b = $a->conj();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(-3, $b->branch());
		$b = $a->inv();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(-3, $b->branch());
		$this->assertEquals(3, $b->uminus_branch());

		$a = new Complex(-2,0,3);
		$this->assertEquals(pi(), $a->arg());
		$this->assertEquals(3, $a->branch());
		$this->assertEquals(-4, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(3, $b->branch());
		$b = $a->conj();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-4, $b->branch());
		$b = $a->inv();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-4, $b->branch());
		$this->assertEquals(3, $b->uminus_branch());

		$a = new Complex(-2,2,3);
		$this->assertEquals(3/4*pi(), $a->arg());
		$this->assertEquals(3, $a->branch());
		$this->assertEquals(-3, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(-1/4*pi(), $b->arg());
		$this->assertEquals(3, $b->branch());
		$b = $a->conj();
		$this->assertEquals(-3/4*pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$b = $a->inv();
		$this->assertEquals(-3/4*pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$this->assertEquals(3, $b->uminus_branch());

		$a = Complex::c_upolar(2,0.5);
		$this->assertEquals(pi(), $a->arg());
		$this->assertEquals(0, $a->branch());
		$this->assertEquals(-1, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(0, $b->branch());
		$b = $a->conj();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-1, $b->branch());
		$b = $a->inv();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-1, $b->branch());
		$this->assertEquals(0, $b->uminus_branch());

		$a = Complex::c_upolar(2,1);
		$this->assertEquals(0, $a->arg());
		$this->assertEquals(1, $a->branch());
		$this->assertEquals(-1, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(1, $b->branch());
		$b = $a->conj();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(-1, $b->branch());
		$b = $a->inv();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(-1, $b->branch());
		$this->assertEquals(1, $b->uminus_branch());

		$a = Complex::c_upolar(2,2.5);
		$this->assertEquals(pi(), $a->arg());
		$this->assertEquals(2, $a->branch());
		$this->assertEquals(-3, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(0, $b->arg());
		$this->assertEquals(2, $b->branch());
		$b = $a->conj();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$b = $a->inv();
		$this->assertEquals(pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$this->assertEquals(2, $b->uminus_branch());

		$a = Complex::c_upolar(2,3.375);
		$this->assertEquals(3/4*pi(), $a->arg());
		$this->assertEquals(3, $a->branch());
		$this->assertEquals(-3, $a->uminus_branch());
		$b = $a->uminus();
		$this->assertEquals(-1/4*pi(), $b->arg());
		$this->assertEquals(3, $b->branch());
		$b = $a->conj();
		$this->assertEquals(-3/4*pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$b = $a->inv();
		$this->assertEquals(-3/4*pi(), $b->arg());
		$this->assertEquals(-3, $b->branch());
		$this->assertEquals(3, $b->uminus_branch());
	}

	public function testBranchOfu ()
	{
		$this->assertEquals(-1, Complex::branch_ofu(-.5));
		$this->assertEquals(-1, Complex::branch_ofu(-.51));
		$this->assertEquals(0, Complex::branch_ofu(-.49));
		$this->assertEquals(0, Complex::branch_ofu(.5));
		$this->assertEquals(1, Complex::branch_ofu(.51));
		$this->assertEquals(0, Complex::branch_ofu(.49));
		$this->assertEquals(-1, Complex::branch_ofu(-1));
		$this->assertEquals(-2, Complex::branch_ofu(-1.5));
		$this->assertEquals(1, Complex::branch_ofu(1));
		$this->assertEquals(1, Complex::branch_ofu(1.5));
	}

/* Arg, Teta and Branch */

	public function testBranch ()
	{
		$a = new Complex(3, 4);
		$this->assertEquals(atan(4/3), $a->theta());
		$a->set_Re(0);
		$this->assertEquals(pi()/2, $a->theta());
		$a->set_Im(0);
		$this->assertEquals(0, $a->theta());

		$a = new Complex(3, 4);
		$this->assertEquals(atan(4/3), $a->theta());
		$a->set_abs(0);
		$this->assertEquals(0, $a->theta());
		$this->assertEquals(0, $a->Re());
		$this->assertEquals(0, $a->Im());

		$a = new Complex(3, 4);
		$this->assertEquals(atan(4/3), $a->theta());
		$a->set_theta(0);
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(0, $a->theta());
		$this->assertEquals(5, $a->Re());
		$this->assertEquals(0, $a->Im());

		$a = new Complex(3, 4);
		$this->assertEquals(atan(4/3), $a->theta());
		$a->set_theta(acos(0));
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(pi()/2, $a->theta());
		$this->assertEquals(0, $a->Re());
		$this->assertEquals(5, $a->Im());

		$a = new Complex(3, 4);
		$this->assertEquals(atan(4/3), $a->theta());
		$this->assertEquals($a->arg(), $a->theta());

		$a = new Complex(3, 4, 2);
		$a->set_branch(4);
		$this->assertEquals(4, $a->branch());
		$this->assertEquals(4*2*pi()+atan(4/3), $a->theta());

		$a = new Complex(3, 4, 3);
		$a->set_branch($a->branch_of( 4*pi() ));
		$this->assertEquals(atan(4/3), $a->arg());
		$this->assertEquals(2, $a->branch());

		$a = Complex::c_polar(5, 2*2*pi()+atan(4/3));
		$this->assertEquals(2, $a->branch());
	}

/* +, -, *, / */

	public function testDiv_Results()
	{
		$err = 1e-12;

		$a = 8;
		$b = new Complex(2, 0);
		$r = Complex::c_div($a,$b);
		$t = new Complex(4, 0, 0);
		$this->assertTrue(Complex::is_equal($t,$r,0,TRUE), '');

		$a = new Complex(8, 0, 2);
		$b = new Complex(2, 0, 2);
		$r = $a->div($b);
		$t = new Complex(4, 0, 0);
		$this->assertTrue(Complex::is_equal($t,$r,$err,TRUE), '');
	}

	public function testDivArguments_Error()
	{
		$this->setExpectedException(
			#'InvalidArgumentException', 'Right Message'
			'InvalidArgumentException'
		);
		$a = array(1);
		$b = new Complex(2, 0, 2);
		$r = Complex::c_div($a,$b);
	}

	public function testDivArguments_Error_2()
	{
		$this->setExpectedException(
			#'InvalidArgumentException', 'Right Message'
			'InvalidArgumentException'
		);
		$a = new Complex(2, 0, 2);
		$b = new Complex(0, 0, 2);
		$r = Complex::c_div($a,$b);
		#echo var_dump($r);
	}

	public function testDivArguments_Error_3()
	{
		$this->setExpectedException(
			#'InvalidArgumentException', 'Right Message'
			'InvalidArgumentException'
		);
		$a = Complex::c_polar(2, 0, 2);
		$b = Complex::c_polar(0, 0, 2);
		$r = Complex::c_div($a,$b);
		#echo var_dump($r);
	}

/* c_atoc, flat */

	public function testAtoc ()
	{
		$a = array(3,4);
		$t = new Complex(3, 4, 0);
		$this->assertEquals($t, Complex::atoc($a));

		$a = array(3,4,2);
		$t = new Complex(3, 4, 2);
		$this->assertEquals($t, Complex::atoc($a));

		$a = array(3,4,2);
		$t = new Complex(3, 4, 5);
		$this->assertEquals($t, Complex::atoc($a, 5));

	}


/* log, exp */

	public function testExp()
	{
		$a = new Complex(3, 4, 0);
		$r = $a->exp();
		$this->assertEquals(exp(3), $r->abs());
		$this->assertEquals($a->Im(), $r->theta());

		$a = new Complex(0, 0);
		$r = $a->exp();
		$this->assertEquals(1, $r->abs());
		$this->assertEquals(0, $r->theta());

		$a = new Complex(0, 0, 2);
		$r = $a->exp();
		$this->assertEquals(1, $r->abs());
		$this->assertEquals(0, $r->theta());
	}

	public function testLog_Error()
	{
		$this->setExpectedException(
			#'InvalidArgumentException', 'Right Message'
			'InvalidArgumentException'
		);
		$a = new Complex(0, 0);
		$r = $a->log();
	}
	
	public function testLog()
	{
		$a = new Complex(1e-323, 0);
		$r = $a->log();
		$this->assertEquals(abs(log(1e-323)), $r->abs());
		$this->assertEquals(pi(), $r->theta());

		$a = new Complex(3, 4, 3);
		$r = $a->log();
		$this->assertEquals(sqrt(log(5)*log(5)+$a->theta()*$a->theta()), $r->abs());
		$this->assertEquals(0, $r->branch());

	}

/* pow, apow */

	public function testPowPolar ()
	{
		$err = 1e-12;

		$a = Complex::c_upolar(0, 2);
		
		$z = Complex::c_upolar(0, 0);
		$r = $a->pow($z);
		$t = new Complex(1, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err, TRUE));
	}

	public function testPow ()
	{
		$err = 1e-12;
		
		$a = new Complex(0, 0, 2);

		$z = new Complex(0, 0);
		$r = $a->pow($z);
		$t = new Complex(1, 0, 0);
		$this->assertEquals($t, $r);

		$z = new Complex(.5, -1);
		$r = $a->pow($z);
		$t = new Complex(0, 0, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));


		$a = new Complex(3, 4, 2);
		
		$z = new Complex(0, 0);
		$r = $a->pow($z);
		$t = new Complex(1, 0, 0);
		$this->assertTrue(Complex::is_equal($t,$r,$err,TRUE), '');

		$z = new Complex(0, .2);
		$r = $a->pow($z);
		$t = new Complex(0.063834651394626, 0.021287936620404, 0);
		$this->assertTrue(Complex::is_equal($t,$r,1e-5,TRUE), '');

		$z = new Complex(.5, 0);
		$r = $a->pow($z);
		$t = new Complex(2, 1, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(.5, .2);
		$r = $a->pow($z);
		$t = new Complex(0.10638136616885, 0.10641052463543, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(0, 2);
		$r = $a->pow($z);
		$t = new Complex(-1.8978091263029E-12, -1.4696140923872E-13, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		
		$z = new Complex(-1/3, 1);
		$r = $a->pow($z);
		$t = new Complex(-7.8112259997596E-7, -2.0206972129886E-7, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(1/3, 1);
		$r = $a->pow($z);
		$t = new Complex(2.3228166142922E-6, -4.1275146391821E-7, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));
		
		$a = Complex::c_upolar(exp(pi()), -3);
		$z = new Complex(-1/3, 1);
		$r = $a->pow($z);
		$this->assertEquals(pi(),$r->arg());
		$this->assertEquals(1,$r->branch());
	}

	public function testApow ()
	{
		$err = 1e-12;

		$a = new Complex(3, 4, 2);

		$z = new Complex(0, 2);
		$res = $a->apow($z, 1);
		#print_r($res[0]);
		$t = array(
			0 => Complex::c_upolar(1.9034907763591E-12, 0.51229999872678)
		);
		$this->assertTrue(Complex::is_equal($t[0], $res[0],$err,TRUE));

		$z = new Complex(0, 2);
		$res = $a->apow($z, 2);
		$this->assertEquals(array(),$res);

		$z = new Complex(-1/3, 1);
		$res = Complex::c_apow($a, $z, 0);
		$t = array(
			0 => new Complex(-7.8112259997596E-7, -2.0206972129886E-7, 0),
			-1 => new Complex(0.0003028520245176, -0.00030814179446006, 0),
			-2 => new Complex(0.061813199275868, 0.2229511175794, 0),
		);
		$this->assertTrue(Complex::is_equal($t[0], $res[0],$err,TRUE));
		$this->assertTrue(Complex::is_equal($t[-1], $res[-1],$err,TRUE));
		$this->assertTrue(Complex::is_equal($t[-2], $res[-2],$err,TRUE));

		$z = new Complex(1/3, 1);
		$res = Complex::c_apow($a, $z, 1);
		$t = array(
			-1 => new Complex(-0.00081333769146394, -0.00096669227585866, 1),
			0 => new Complex(2.3228166142922E-6, -4.1275146391821E-7, 1),
			1 => new Complex(-1.5013400221071E-9, 4.1419766403179E-9, 1),
		);
		$this->assertTrue(Complex::is_equal($t[-1], $res[-1],$err,TRUE));
		$this->assertTrue(Complex::is_equal($t[0], $res[0],$err,TRUE));
		$this->assertTrue(Complex::is_equal($t[1], $res[1],$err,TRUE));

		$z = new Complex(-1/3, 1);
		$res = Complex::c_apow($a, $z);
		$t = array(
			0 => new Complex(-7.8112259997596E-7, -2.0206972129886E-7, 0),
		);
		$this->assertTrue(Complex::is_equal($t[0], $res[0],$err,TRUE));

		$z = new Complex(1/3, 1);
		$res = Complex::c_apow($a, $z);
		$t = array(
			0 => new Complex(2.3228166142922E-6, -4.1275146391821E-7, 1),
		);
		$this->assertTrue(Complex::is_equal($t[0], $res[0],$err,TRUE));
	}

}

?>
