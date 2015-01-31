<?php
/**
* @package PHPComplex
*/
namespace enove\PHPComplex;

interface iComplex
{
	static function is_complex ($a);
	static function is_a_complex ($a);
	
	function __construct ($x = NULL, $y = NULL, $s = NULL);
	static function polar ($r, $teta = NULL);
	function Re ();
	function set_Re ($x = NULL);
	function Im ();
	function set_Im ($x = NULL);
	function get_s ();
	function set_s ($s = NULL);
	function abs ();
	function conj ();
	function arg ();
	function teta ($s = NULL);
	function s_teta ($teta);
	function sqrt ($s = NULL);
	function inv ($s = NULL);
	function log ($s = NULL);
	function exp ();
	function flat ();
	
	static function atoc ($a, $s = NULL);
	
	static function c_add ($a, $b, $s = NULL);
	static function c_sub ($a, $b, $s = NULL);
	static function c_mult ($a, $b, $s = NULL);
	static function c_div ($a, $b, $s = NULL);
	static function c_pow ($a, $z, $s = NULL);
	static function c_apow ($w, $z, $s = NULL);
	
	public function __toString ();
	
	#function add ($b, $s = NULL);
	#function sub ($b, $s = NULL);
	#function mult ($b, $s = NULL);
	#function div ($b, $s = NULL);
	function pow ($z, $s = NULL);
	#function apow ($z, $s = NULL);
}
