<?php
/**
* @package PHPComplex
*/
namespace enove\PHPComplex;

require_once 'iComplex.php';

/* gettext domain */
define('PHPCOMPLEX_DOMAIN','phpcomplex');

if (!defined('PHPCOMPLEX_CLASS')) {
	define('PHPCOMPLEX_CLASS',__NAMESPACE__.'\Complex');
}

/**
 *
 */
class Complex implements iComplex
{
	protected $re;
	protected $im;
	protected $s;
	
	/**
	 *
	 */
	static function is_complex ($a)
	{
		return is_object($a) && (get_class($a) == get_called_class());
	}

	/**
	 *
	 */
	static function is_a_complex ($a)
	{
		return is_a($a, get_called_class());
	}

	/*
	 *
	 */
	function __construct ($x = NULL, $y = NULL, $s = NULL)
	{
		if ( is_null($x) ) {
			$x = 0;
		}
		if ( is_null($y) ) {
			$y = 0;
		}
		if ( is_null($s) ) {
			$s = 0;
		}
		if ( !is_numeric($x) || !is_numeric($y) ) { // Remember this allows numeric strings.
			throw new InvalidArgumentException(dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument'));
		}
		$this->set_Re($x);
		$this->set_Im($y);
		$this->set_s($s);
	}
	
	/*
	 * Alias of set_Re()
	 */
	function Re ()
	{
		return $this->set_Re();
	}
	
	/*
	 *
	 */
	function set_Re ($x = NULL)
	{
		if ( is_numeric($x) ) {
			$this->re = $x;
		}
		return $this->re;
	}
	
	/*
	 * Alias of set_Im()
	 */
	function Im ()
	{
		return $this->set_Im();
	}
	
	/*
	 *
	 */
	function set_Im ($x = NULL)
	{
		if ( is_numeric($x) ) {
			$this->im = $x;
		}
		return $this->im;
	}
	
	/*
	 *
	 */
	function get_s ()
	{
		return $this->set_s();
	}
	
	/*
	 *
	 */
	function set_s ($s = NULL)
	{
		if ( !is_null($s) ) {
			if ( !is_int($s) ) {
				return FALSE;
			}
			$this->s = $s;
		}
		return $this->s;
	}
	
	/*
	 *
	 */
	function abs ()
	{
		return sqrt(pow($this->Re(),2) + pow($this->Im(),2));
	}
	
	/*
	 * conjugate
	 */
	function conj ()
	{
		return new static($this->Re(), -$this->Im(), $this->get_s());
	}
	
	/*
	 *
	 */
	function arg ()
	{
		if ( ($this->abs() == 0) ) {
			return 0;
		}
		return atan2($this->Im(), $this->Re());
	}
	
	/*
	 *
	 */
	function teta ($s = NULL)
	{
		if ( !is_null($s) ) {
			#return $this->arg() + 2*pi()*$s;
			throw new LogicException('invalid call');
		}
		return $this->arg() + 2*pi()*$this->get_s();
	}
	
	/*
	 * 
	 */
	function s_teta ($teta)
	{
		$max_s_error = 1e-4;
		$st = ( $teta - $this->arg() )/2/pi();
		$s = (int) round($st);
		if ( (abs($st - $s) > $max_s_error) ) {
			#throw new LogicException('invalid s');
			return FALSE;
		}
		return $s;
	}
	
	/*
	 *
	 */
	function sqrt ($s = NULL)
	{
		if ( is_null($s) ) {
			$s = $this->get_s();
		}
		$rc = sqrt(($this->abs() + $this->Re())/2);
		$ic = (($this->Im() < 0)? -1 : 1) * sqrt(($this->abs() - $this->Re())/2);
		return array(
			new static(
				$rc, 
				$ic, 
				$s
			),
			new static(
				-$rc, 
				-$ic, 
				$s
			),
		);
	}
	
	/*
	 *
	 */
	function inv ($s = NULL)
	{
		if ( is_null($s) ) {
			$s = $this->get_s();
		}
		if ( ($this->abs() == 0) ) {
			return new static(0,0,$s);
		}
		$r2 = pow($this->abs(),2);
		$c = new static(
			$this->Re()/$r2, 
			-$this->Im()/$r2,
			$s
		);
		return $c;
	}
	
	/*
	 *
	 */
	function log ($s = NULL)
	{
		$c = new static(
			log($this->abs()), 
			$this->teta()
		);
		if ( is_null($s) ) {
			$s = $this->get_s();
		}
		$c->set_s($s);
		return $c;
	}
	
	/**
	 * @return complex
	 */
	function exp ()
	{
		$c = static::polar(
			exp($this->Re()), 
			$this->Im()
		);
		return $c;
	}
	

	/*
	 * @return mixed A float if Im == 0 or the complex.
	 */
	function flat ()
	{
		if ( ($this->Im() == 0) ) {
			return $this->Re();
		}
		return $this;
	}

	/*
	 * arg will be changed to ]-pi(),pi()], and s will be set
	 */
	static function polar ($r, $teta = NULL)
	{
		if ( is_null($teta) ) {
			$teta = 0;
		}
		$c = new static(
			$r*cos($teta), 
			$r*sin($teta)
		);
		$c->set_s($c->s_teta($teta));
		return $c;
	}
	
	/**
	 * array( $re, $im ) -> complex object
	 */
	static function atoc ($a, $s = NULL) {
		if ( isset($a[2]) && is_null($s) ) {
			$s = $a[2];
		}
		$c = new static($a[0], $a[1], $s);
		return $c;
	}

	/*
	 *
	 */
	static function c_add ($a, $b, $s = NULL)
	{
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}
		if ( is_null($s) ) {
			$s = $a->get_s();
		}
		$c = new static(
			$a->Re()+$b->Re(),
			$a->Im()+$b->Im(),
			$s
		);
		return $c;
	}

	/*
	 *
	 */
	static function c_sub ($a, $b, $s = NULL)
	{
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}
		if ( is_null($s) ) {
			$s = $a->get_s();
		}
		$c = new static(
			$a->Re()-$b->Re(),
			$a->Im()-$b->Im(),
			$s
		);
		return $c;
	}

	/*
	 *
	 */
	static function c_mult ($a, $b, $s = NULL)
	{
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}
		$c = new static(
			$a->Re()*$b->Re()-$a->Im()*$b->Im(),
			$a->Im()*$b->Re()+$a->Re()*$b->Im()
		);
		if ( is_null($s) ) {
			$s = $c->s_teta( $a->teta() + $b->teta() );
		}
		$c->set_s($s);
		return $c;
	}

	/*
	 *
	 */
	static function c_div ($a, $b, $s = NULL)
	{
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}
		$r2b = pow($b->Re(),2) + pow($b->Im(),2);
		if ( ($r2b == 0) ) {
			throw new DomainException(dgettext(PHPCOMPLEX_DOMAIN, 'invalid result'));
		}
		$c = new static(
			($a->Re()*$b->Re()+$a->Im()*$b->Im())/$r2b,
			($a->Im()*$b->Re()-$a->Re()*$b->Im())/$r2b
		);
		if ( is_null($s) ) {
			$s = $c->s_teta( $a->teta() - $b->teta() );
		}
		$c->set_s($s);
		return $c;
	}

	/**
	 * @return complex $a ** $z
	 */
	static function c_pow ($a, $z, $s = NULL)
	{
		$av = static::c_apow($a, $z, $s);
		reset($av);
		
		if ( is_null($s) ) {
			#return current($av);
			return $av[0];
		}
		
		while ( (list($k, $v) = each($av)) ) {
			$key = $k;
			if ( ($v->arg() >=  0) ) {
				break;
			}
		}
		
		/*
		// if $av was not ordered
		reset($av);
		list($key, $v) = each($av);
		$p = $v->arg();
		while ( (list($k, $v) = each($av)) ) {
			if ( ( ($p < 0) || ($v->arg() >= 0) ) && (abs($v->arg()) < abs($p)) ) {
				$key = $k;
				$p = $v->arg();
			}
		}
		*/
		
		return $av[$key];
	}


	/**
	 * @return array
	 */
	static function c_apow ($w, $z, $s = NULL)
	{
		$a = clone($w);
		$sa = $a->get_s();
		#echo 'c_apow a ',$a,"\n";

		#$r_v = pow($a->abs(), $z->Re()) * exp(-$z->Im() * ( $a->teta() + 2*pi()*$k ));
		#$teta_v = $z->Im() * log($a->abs()) + $z->Re() * ( $a->teta() + 2*pi()*$k );
		#$v = c_polar($r_v, $teta_v);

		if ( ( $a->abs() == 0 ) ) {
			$av = array( 
				0 => new static(1, 0, $s)
			);
			return $av;
		}

		if ( ( $z->Re() == 0 ) ) {
			if ( ( $z->Im() == 0 ) ) {
				$av = array( 
					0 => new static(1, 0, $s)
				);
				return $av;
			}
			
			$r_v = exp(-$z->Im() * $a->teta());
			$teta_v = $z->Im() * log($a->abs());
			$av = array( 
				0 => static::polar($r_v, $teta_v)
			);
			return $av;
		}
		
		if ( is_null($s) ) {
			/* return only the k=0 value */
			$av = array( 
				0 => static::c_mult($z, $a->log())->exp()
			);
			return $av;
		}

		#echo 'c_apow 1/abs(c) ',1/abs($z->Re()),"\n";
		#echo 'c_apow s ',$s,"\n";

		$tv = ( $z->Im() * log($a->abs()) + $z->Re() * $a->teta() )/(2*pi()); 
		if ( ( $z->Re() > 0 ) ) {
			$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() ) + 1;
		} else {
			$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() );
		}

		if ( ( $z->Re() > 0 ) ) {
			$f = (int) floor( (-$tv + $s + 1/2)/$z->Re() );
			do {
				$a->set_s($sa+$k);
				$av[$k] = static::c_mult($z, $a->log())->exp();
				$k++;
			} while ( ($k <= $f) );
		} else {
			$f = (int) ceil( (-$tv + $s + 1/2)/$z->Re() );
			do {
				$a->set_s($sa+$k);
				$av[$k] = static::c_mult($z, $a->log())->exp();
				$k--;
			} while ( ($k >= $f) );
		}
		
		return $av;
	}
	
	/*
	 *
	 */
	public function __toString ()
	{
		#$format='%13.5g';
		$format='%.5g';
		$str = sprintf($format, $this->Re()); 
		if ( ($this->Re() >= 0) ) {
			$str = ' ' . $str;
		}
		$format='%.5g';
		if ( ($this->Im() > 0) ) {
			$str .= ' + ' . sprintf($format, $this->Im()) . 'i'; 
		} elseif ( ($this->Im() < 0) ) {
			$str .= ' - ' . sprintf($format, abs($this->Im())) . 'i'; 
		}
		$str .= ' (' . $this->arg()*180/pi() . 'º';
		$str .= (($this->get_s() == 0)? ')' : '(' . $this->get_s() . '))');
		#return str_pad($str,26); 
		return $str; 
	}

// Object oriented style

	/*
	 *
	 */
	/*
	function add ($b, $s = NULL)
	{
		return static::c_add($this, $b, $s);
	}
	*/

	/*
	 *
	 */
	function pow ($z, $s = NULL)
	{
		return static::c_pow($this, $z, $s);
	}

}
