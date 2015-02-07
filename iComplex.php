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
	const PHPCOMPLEX_MODE_COORDS = 0;
	const PHPCOMPLEX_MODE_POLAR  = 1;

	public function __construct($x = NULL, $y = NULL, $s = NULL);
	static public function c_coords($x = NULL, $y = NULL, $s = NULL);
	static public function c_polar($r, $theta = NULL, $umode = NULL);
	static public function c_upolar($r, $utheta = NULL);
	static public function atoc($a, $s = NULL);

	public function set_coords($x = NULL, $y = NULL, $s = NULL);
	public function set_polar($r = NULL, $theta = NULL, $umode = NULL);

	public function conj($s = NULL);
	public function inv($s = NULL);
	public function uminus($s = NULL);
	public function sqrt($s = NULL);
	public function asqrt($s = NULL);
	public function log($s = NULL);
	public function exp();

	public function add($b, $s = NULL);
	public function sub($b, $s = NULL);
	public function mult($b, $s = NULL);
	public function div($b, $s = NULL);
	public function pow($z, $s = NULL);
	public function apow($z, $s = NULL);

	public function flat();

	static public function c_add($a, $b, $s = NULL);
	static public function c_sub($a, $b, $s = NULL);
	static public function c_mult($a, $b, $s = NULL);
	static public function c_div($a, $b, $s = NULL);
	static public function c_pow($a, $z, $s = NULL);
	static public function c_apow($a, $z, $s = NULL);

	public function mode();
	public function set_mode($mode, $update = NULL);

	public function Re();
	public function set_Re($x);
	public function Im();
	public function set_Im($x);
	public function abs();
	public function set_abs($x);
	public function arg();
	public function theta();
	public function set_theta($theta);
	public function branch($theta = NULL);
	public function set_branch($s);
	public function uminus_branch();

	static public function is_complex($a);
	static public function is_a_complex($a);

	static public function is_equal($a, $b, $err = NULL, $strict = NULL);

	public function __toString();
}
