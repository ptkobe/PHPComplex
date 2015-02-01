<?php
/**
* @package PHPComplex
* File c_functions.php
*/

function is_complex($a)
{
	$class = PHPCOMPLEX_CLASS;
	return $class::is_complex($a);
}

function c_polar($r, $ang = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	return $class::polar($r, $ang);
}

function atoc($a, $s = NULL) {
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $s);
}

function c_add($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_sub($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_mult($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_div($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_pow($a, $z, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $z, $s);
}

function c_apow($a, $z, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $z, $s);
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
