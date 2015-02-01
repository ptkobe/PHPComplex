<?php
/**
* @package PHPComplex
* File iComplex.php
*/
namespace enove\PHPComplex;

interface iComplex
{
	static public function is_complex($a);
	static public function is_a_complex($a);
	
	public function __construct($x = NULL, $y = NULL, $s = NULL);
	static public function polar($r, $teta = NULL);

	public function Re();
	public function Im();
	public function arg();

	public function get_s();
	public function set_s($s = NULL);
	public function s_theta($teta);
	public function theta($s = NULL);

	public function abs();
	public function conj();

	public function flat();

	public function add($b, $s = NULL);
	public function sub($b, $s = NULL);
	public function mult($b, $s = NULL);
	public function div($b, $s = NULL);

	public function pow($z, $s = NULL);
	public function apow($z, $s = NULL);

	public function sqrt($s = NULL);
	public function inv($s = NULL);
	public function log($s = NULL);
	public function exp();

	static public function atoc($a, $s = NULL);

	static public function c_add($a, $b, $s = NULL);
	static public function c_sub($a, $b, $s = NULL);
	static public function c_mult($a, $b, $s = NULL);
	static public function c_div($a, $b, $s = NULL);
	static public function c_pow($a, $z, $s = NULL);
	static public function c_apow($w, $z, $s = NULL);

	public function __toString();

	#public function set_Re($x = NULL);
	#public function set_Im($x = NULL);
}
