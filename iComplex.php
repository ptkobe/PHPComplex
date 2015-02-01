<?php
/**
* @package PHPComplex
* File iComplex.php
*/
namespace enove\PHPComplex;

/**
 * Interface iComplex
 */
interface iComplex
{
	static public function is_complex($a);
	static public function is_a_complex($a);
	
	public function __construct($x = NULL, $y = NULL, $s = NULL);
	static public function polar($r, $theta = NULL);
	static public function atoc($a, $s = NULL);

	public function Re();
	public function Im();
	public function arg();

	public function get_s();
	public function set_s($s = NULL);
	public function s_theta($theta);
	public function theta();

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

	static public function c_add($a, $b, $s = NULL);
	static public function c_sub($a, $b, $s = NULL);
	static public function c_mult($a, $b, $s = NULL);
	static public function c_div($a, $b, $s = NULL);
	static public function c_pow($a, $z, $s = NULL);
	static public function c_apow($w, $z, $s = NULL);

	public function __toString();

	#protected function set_Re($x = NULL);
	#protected function set_Im($x = NULL);
}
