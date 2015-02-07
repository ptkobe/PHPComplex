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
		$t = new Complex(5,0,0);
		$this->assertEquals(5, $a->abs());
		$this->assertEquals(5, $a->Re());
		$this->assertEquals(0, $a->Im());
		$this->assertEquals(0, $a->arg());
		#$this->assertEquals($t, $a);
	}

/* Arg, Teta and S */

	public function testGetS_SetS ()
	{
		$a = new Complex(3, 4, 2);
		$a->set_branch(4);

		// Assert
		$this->assertEquals(4, $a->branch());
	}

	public function testTheta_SetS ()
	{
		$a = new Complex(3, 4, 2);
		$a->set_branch(4);

		// Assert
		$this->assertEquals(4*2*pi()+atan(4/3), $a->theta());
	}

	public function testArg_SetSTheta ()
	{
		$a = new Complex(3, 4, 3);
		$a->set_branch($a->branch( 4*2*pi()+atan(4/3) ));

		// Assert
		$this->assertEquals(atan(4/3), $a->arg());
	}

	public function testGetS_Polar ()
	{
		$a = Complex::c_polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(2, $a->branch());
	}

	public function testTheta_NullS ()
	{
		$a = new Complex(3, 4);

		// Assert
		$this->assertEquals(atan(4/3), $a->theta());
	}

	public function testThetaArg_NullS ()
	{
		$a = new Complex(3, 4);

		// Assert
		$this->assertEquals($a->arg(), $a->theta());
	}

/* +, -, *, / */ // @expectedException DomainException

	public function testDiv_Arguments()
	{
		#$err = 1e-15;

		$a = 8;
		$b = new Complex(2, 0);
		$r = Complex::c_div($a,$b);
		$t = new Complex(4, 0, 0);
		$this->assertTrue(Complex::is_equal($t,$r,0,TRUE), '');

		$a = new Complex(2, 0, 2);
		$b = new Complex(0, 0, 2);
		@$r = Complex::c_div($a,$b);

		$a = new Complex(0, 0, 2);
		$b = new Complex(0, 0, 2);
		@$r = Complex::c_div($a,$b);
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
			'PHPUnit_Framework_Error_Warning'
		);
		$a = new Complex(2, 0, 2);
		$b = new Complex(0, 0, 2);
		$r = Complex::c_div($a,$b);
		#echo var_dump($r);
	}

	public function testDiv_Results()
	{
		$err = 1e-12;

		$a = new Complex(8, 0, 2);
		$b = new Complex(2, 0, 2);
		$r = $a->div($b);
		$t = new Complex(4, 0, 0);
		$this->assertTrue(Complex::is_equal($t,$r,$err,TRUE), '');

		$a = new Complex(2, 0, 2);
		$b = new Complex(0, 0, 2);
		$r = @Complex::c_div($a,$b);
		$this->assertEquals(FALSE, $r);

		$a = Complex::c_polar(2, 0, 2);
		$b = Complex::c_polar(0, 0, 2);
		$r = @Complex::c_div($a,$b);
		$this->assertEquals(FALSE, $r);

		$a = new Complex(0, 0, 2);
		$b = new Complex(0, 0, 2);
		$r = @Complex::c_div($a,$b);
		$this->assertEquals(FALSE, $r);
	}

/* c_atoc, flat */

	public function testAtoc ()
	{
		$a = array(3,4,2);
		$t = new Complex(3, 4, 2);
		$this->assertEquals($t, Complex::atoc($a));

		$a = array(3,4);
		$t = new Complex(3, 4, 0);
		$this->assertEquals($t, Complex::atoc($a));
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

	public function testLog()
	{
		$a = new Complex(0, 0);
		$r = $a->log();
		$this->assertEquals(-INF, $r->Re());
		$this->assertEquals(0, $r->Im());
		$this->assertEquals(0, $r->branch());

		$a = new Complex(0, 0, 2);
		$r = $a->log();
		$this->assertEquals(-INF, $r->Re());
		$this->assertEquals(0, $r->Im());
		$this->assertEquals(2, $r->branch());
	}

/* pow, apow */

	public function testPow ()
	{
		$err = 1e-12;

		$a = new Complex(0, 0, 2);

		$z = new Complex(.5, -1);
		$t = new Complex(1, 0, 0);
		$this->assertEquals($t, $a->pow($z));

		$z = new Complex(0, 0);
		$t = new Complex(1, 0, 0);
		$this->assertEquals($t, $a->pow($z));

		$z = new Complex(0, 0);
		$t = new Complex(1, 0, 4);
		$this->assertEquals($t, $a->pow($z, 4));

		$a = new Complex(3, 4, 2);
		
		$z = new Complex(.5, 0);
		$r = $a->pow($z);
		$this->assertEquals(2, $r->Re());
		$this->assertEquals(1, $r->Im());
		$this->assertEquals(1, $r->branch());

		$z = new Complex(.5, .2);
		$r = $a->pow($z);
		$this->assertEquals(0.10638136616885, $r->Re());
		$this->assertEquals(0.10641052463543, $r->Im());
		$this->assertEquals(1, $r->branch());

		$z = new Complex(0, .2);
		$t = new Complex(0.063834651394626, 0.021287936620404, 0);
		$r = $a->pow($z);
		$this->assertEquals(0.063834651394626, $r->Re());
		$this->assertEquals(0.021287936620404, $r->Im());
		$this->assertEquals(0, $r->branch());

		$z = new Complex(0, 0);
		$t = new Complex(1, 0, 0);
		$this->assertEquals($t, $a->pow($z));

		$z = new Complex(.5, 0);
		$r = Complex::c_pow($a, $z, 0);
		$t = new Complex(2, 1, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(0, 2);
		$r = Complex::c_pow($a, $z, 0);
		$t = new Complex(-1.8978091263029E-12, -1.4696140923872E-13, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(-1/3, 1);
		$r = Complex::c_pow($a, $z, 0);
		$t = new Complex(0.061813199275868, 0.2229511175794, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(1/3, 1);
		$r = Complex::c_pow($a, $z, 1);
		$t = new Complex(-1.5013400221071E-9, 4.1419766403179E-9, 1);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(-1/3, 1);
		$r = Complex::c_pow($a, $z);
		$t = new Complex(-7.8112259997596E-7, -2.0206972129886E-7, 0);
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));

		$z = new Complex(1/3, 1);
		$r = Complex::c_pow($a, $z);
		$t = new Complex(2.3228166142922E-6, -4.1275146391821E-7, 1);
		#echo $r, $r->Re(), $r->Im();
		$this->assertTrue(Complex::is_equal($t, $r,$err,TRUE));
	}

	public function testApow ()
	{
		$err = 1e-12;

		$a = new Complex(3, 4, 2);

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
