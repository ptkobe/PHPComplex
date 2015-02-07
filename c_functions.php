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
	return Complex::$method($a);
}

function c_polar($r, $theta = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($r, $theta);
}

function atoc($a, $s = NULL) {
	$method = __FUNCTION__;
	return Complex::$method($a, $s);
}

function c_add($a, $b, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b, $s);
}

function c_sub($a, $b, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b, $s);
}

function c_mult($a, $b, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b, $s);
}

function c_div($a, $b, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $b, $s);
}

function c_pow($a, $z, $s = NULL)
{
	$method = __FUNCTION__;
	return Complex::$method($a, $z, $s);
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

function c_log($a, $s = NULL)
{
	return $a->log($s);
}
