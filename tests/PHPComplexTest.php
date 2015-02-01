<?php
/**
* @package PHPComplex
*/

// see autoloading
require_once '../Complex.php';

class PHPComplexTest extends PHPUnit_Framework_TestCase
{

/* new and polar */

	public function testNew_Abs ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);

		// Assert
		$this->assertEquals(5, $a->abs());
	}

	public function testPolar_Abs ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(5, $a->abs());
	}

	public function testNew_Arg_1 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);

		// Assert
		$this->assertEquals(atan(4/3), $a->arg());
	}

	public function testNew_Arg_2 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, -4, 2);

		// Assert
		$this->assertEquals(-atan(4/3), $a->arg());
	}

	public function testNew_Arg_3 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(-3, 4, 2);

		// Assert
		$this->assertEquals(pi()-atan(4/3), $a->arg());
	}

	public function testNew_Arg_4 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(-3, -4, 2);

		// Assert
		$this->assertEquals(-pi()+atan(4/3), $a->arg());
	}

	public function testPolar_Arg ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(atan(4/3), $a->arg());
	}

	public function testNew_Theta ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);

		// Assert
		$this->assertEquals(2*2*pi()+atan(4/3), $a->theta());
	}

	public function testPolar_Theta ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(2*2*pi()+atan(4/3), $a->theta());
	}

	public function testNew_Re ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);

		// Assert
		$this->assertEquals(3, $a->Re());
	}

	public function testPolar_Re ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(3, $a->Re());
	}

	public function testNew_Im ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);

		// Assert
		$this->assertEquals(4, $a->Im());
	}

	public function testPolar_Im ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(4, $a->Im());
	}

	public function testNew0_Abs ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);

		// Assert
		$this->assertEquals(0, $a->abs());
	}

	public function testPolar0_Abs ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(0, 2*2*pi());

		// Assert
		$this->assertEquals(0, $a->abs());
	}

	public function testNew0_Arg ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);

		// Assert
		$this->assertEquals(0, $a->arg());
	}

	public function testPolar0_Arg ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(0, 2*2*pi());

		// Assert
		$this->assertEquals(0, $a->arg());
	}

	public function testNew0_Theta ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);

		// Assert
		$this->assertEquals(2*pi()*2, $a->theta());
	}

	public function testPolar0_Theta ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(0, 2*2*pi());

		// Assert
		$this->assertEquals(2*pi()*2, $a->theta());
	}

	public function testNew_NULL_0 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class();
		$t = new $class(0,0,0);

		// Assert
		$this->assertEquals($t, $a);
	}

	public function testNew_NULL_1 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3);
		$t = new $class(3,0,0);

		// Assert
		$this->assertEquals($t, $a);
	}

	public function testNew_NULL_2 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(NULL,4);
		$t = new $class(0,4,0);

		// Assert
		$this->assertEquals($t, $a);
	}

	public function testNew_NULL_3 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(NULL,NULL,2);
		$t = new $class(0,0,2);

		// Assert
		$this->assertEquals($t, $a);
	}

	public function testPolar_NULL ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5);
		$t = new $class(5,0,0);

		// Assert
		$this->assertEquals($t, $a);
	}

/* Arg, Teta and S */

	public function testGetS_SetS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$a->set_s(4);

		// Assert
		$this->assertEquals(4, $a->get_s());
	}

	public function testTheta_SetS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$a->set_s(4);

		// Assert
		$this->assertEquals(4*2*pi()+atan(4/3), $a->theta());
	}

	public function testArg_SetSTheta ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 3);
		$a->set_s($a->s_theta( 4*2*pi()+atan(4/3) ));

		// Assert
		$this->assertEquals(atan(4/3), $a->arg());
	}

	public function testGetS_Polar ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = $class::polar(5, 2*2*pi()+atan(4/3));

		// Assert
		$this->assertEquals(2, $a->get_s());
	}

	public function testTheta_NullS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4);

		// Assert
		$this->assertEquals(atan(4/3), $a->theta());
	}

	public function testThetaArg_NullS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4);

		// Assert
		$this->assertEquals($a->arg(), $a->theta());
	}

/* +, -, *, / */

/* c_atoc, flat */

	public function testAtoc_1 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = array(3,4,2);
		$t = new $class(3, 4, 2);

		// Assert
		$this->assertEquals($t, $class::atoc($a));
	}

	public function testAtoc_2 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = array(3,4);
		$t = new $class(3, 4, 0);

		// Assert
		$this->assertEquals($t, $class::atoc($a));
	}


/* log, exp */

	public function testExp_Exp ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 0);
		$t = new $class(-13.128783081462, -15.200784463068, 1);

		// Assert
		$this->assertEquals($t, $a->exp());
	}

	public function testExp_0Exp ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0);
		$t = new $class(1, 0, 0);

		// Assert
		$this->assertEquals($t, $a->exp());
	}

	public function testExp_0ExpS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);
		$t = new $class(1, 0, 0);

		// Assert
		$this->assertEquals($t, $a->exp());
	}

	public function testLog_0Log ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0);
		$t = new $class(-INF, 0, 0);

		// Assert
		$this->assertEquals($t, $a->log());
	}

	public function testLog_0logS ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);
		$t = new $class(-INF, 2*2*pi(), 2);

		// Assert
		$this->assertEquals($t, $a->log());
	}

/* pow, apow */

	public function testPow_Pow_1 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(.5, 0);
		$t = new $class(2, 1, 1);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_Pow_2 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(.5, .2);
		$t = new $class(0.10638136616885, 0.10641052463543, 1);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_Pow_3 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(0, .2);
		$t = new $class(0.063834651394626, 0.021287936620404, 0);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_Pow0 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(0, 0);
		$t = new $class(1, 0, 0);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_0Pow ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);
		$z = new $class(.5, -1);
		$t = new $class(1, 0, 0);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_0Pow0 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);
		$z = new $class(0, 0);
		$t = new $class(1, 0, 0);

		// Assert
		$this->assertEquals($t, $a->pow($z));
	}

	public function testPow_0Pow0S ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(0, 0, 2);
		$z = new $class(0, 0);
		$t = new $class(1, 0, 4);

		// Assert
		$this->assertEquals($t, $a->pow($z, 4));
	}

	public function testApow_APow_1 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(.5, 0);
		$res = $class::c_apow($a, $z, 0);
		$t = array(
			-3 => new $class(-2, -1, 0),
			-2 => new $class(2, 1, 0),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

	public function testApow_APow_2 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(0, 2);
		$res = $class::c_apow($a, $z, 0);
		$t = array(
			0 => new $class(-1.8978091263029E-12, -1.4696140923872E-13, 1),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

	public function testApow_APow_3 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(-1/3, 1);
		$res = $class::c_apow($a, $z, 0);
		$t = array(
			0 => new $class(-7.8112259997596E-7, -2.0206972129886E-7, 0),
			-1 => new $class(0.0003028520245176, -0.00030814179446006, 0),
			-2 => new $class(0.061813199275868, 0.2229511175794, 0),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

	public function testApow_APow_4 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(1/3, 1);
		$res = $class::c_apow($a, $z, 1);
		$t = array(
			-1 => new $class(-0.00081333769146394, -0.00096669227585866, 1),
			0 => new $class(2.3228166142922E-6, -4.1275146391821E-7, 1),
			1 => new $class(-1.5013400221071E-9, 4.1419766403179E-9, 1),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

	public function testApow_APow_5 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(-1/3, 1);
		$res = $class::c_apow($a, $z);
		$t = array(
			0 => new $class(-7.8112259997596E-7, -2.0206972129886E-7, 0),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

	public function testApow_APow_6 ()
	{
		$class = PHPCOMPLEX_CLASS;
		$a = new $class(3, 4, 2);
		$z = new $class(1/3, 1);
		$res = $class::c_apow($a, $z);
		$t = array(
			0 => new $class(2.3228166142922E-6, -4.1275146391821E-7, 1),
		);

		// Assert
		$this->assertEquals($t, $res);
	}

}

?>
