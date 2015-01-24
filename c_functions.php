<?php
/**
* @package Math
* File c_functions.php
*/

/**
 * array( $re, $im ) -> complex object
 */
function atoc ($a, $s = NULL) {
	$class = PHPCOMPLEX_CLASS;
	$c = new $class($a[0], $a[1], $s);
	return $c;
}

function is_complex ($a)
{
	$class = PHPCOMPLEX_CLASS;
	return $class::is_complex($a);
}

function c_add ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_sub ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_mult ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_div ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_polar ($r, $ang = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($r, $ang);
}

function c_pow ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

function c_apow ($a, $b, $s = NULL)
{
	$class = PHPCOMPLEX_CLASS;
	$method = __FUNCTION__;
	return $class::$method($a, $b, $s);
}

// Procedural type style

function c_exp ($a)
{
	return $a->exp();
}

function c_log ($a, $s = NULL)
{
	return $a->log($s);
}
