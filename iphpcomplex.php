<?php
/**
* PHPComplex
*/

interface iPHPComplex
{
	static function is_complex ($a);
	static function is_a_complex ($a);
	function __construct ($x = 0, $y = 0, $s = 0);
	function Re ();
	function Im ();
	function abs ();
	function conj ();
	function arg ();
	function flat ();
	static function c_polar ($r, $teta = 0);
	function teta ($s = NULL);
	function get_s ($teta = NULL);
	function set_s ($s, $teta_mode = NULL);
	function sqrt ($s = NULL);
	function inv ($s = NULL);
	function log ($s = NULL);
	function exp ();
	static function c_pow ($a, $z, $s = NULL);
	static function c_apow ($w, $z, $s = NULL);
	static function c_add ($a, $b, $s = NULL);
	static function c_sub ($a, $b, $s = NULL);
	static function c_mult ($a, $b, $s = NULL);
	static function c_div ($a, $b, $s = NULL);
	#public function __toString ();
	#function mult ($b, $s = NULL);
	function pow ($b, $s = NULL);
}
