<?php
/**
* @package PHPComplex
* File c_functions.php
*/

use enove\PHPComplex\Complex;

function is_complex($a)
{
	$method = __FUNCTION__;
	return Complex::$method($a);
}

function c_coords($x, $y = NULL, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($x, $y, $s);
}

function c_polar($r, $theta = NULL, $umode = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($r, $theta, $umode);
}

function c_upolar($r, $utheta = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($r, $utheta);
}

function atoc($a, $s = NULL) {
	$method = __FUNCTION__;
	return Complex::$method($a, $s);
}

function c_add($a, $b)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b);
}

function c_sub($a, $b)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b);
}

function c_mult($a, $b)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b);
}

function c_div($a, $b)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b);
}

function c_pow($a, $z)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $z);
}

function c_apow($a, $z, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $z, $s);
}

// Procedural type style

function c_exp($a)
{
	return $a->exp();
}

function c_log($a)
{
	return $a->log();
}
