<?php
/**
* @package Math
*/

require_once 'iphpcomplex.php';

/* gettext domain */
define('PHPCOMPLEX_DOMAIN','phpcomplex');

if (!defined('PHPCOMPLEX_CLASS')) {
	define('PHPCOMPLEX_CLASS','PHPComplex');
}

/**
 *
 */
Class PHPComplex implements iPHPComplex
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
		$this->re = $x;
		$this->im = $y;
		$this->set_s($s);
	}
	
	/*
	 *
	 */
	function Re ()
	{
		return $this->re;
	}
	
	/*
	 *
	 */
	function Im ()
	{
		return $this->im;
	}
	
	/*
	 *
	 */
	function abs ()
	{
		return sqrt(pow($this->re,2) + pow($this->im,2));
	}
	
	/*
	 * conjugate
	 */
	function conj ()
	{
		return new static($this->re, -$this->im, $this->s);
	}
	
	/*
	 *
	 */
	function arg ()
	{
		if ( ($this->abs() == 0) ) {
			return 0;
		}
		return atan2($this->im, $this->re);
	}
	
	/*
	 * @return mixed A float if Im == 0 or the complex.
	 */
	function flat ()
	{
		if ( ($this->im == 0) ) {
			return $this->re;
		}
		return $this;
	}

	/*
	 * arg will be changed to [-pi(),pi()], and $s will be set
	 */
	static function c_polar ($r, $teta = NULL)
	{
		if ( is_null($teta) ) {
			$teta = 0;
		}
		$c = new static(
			$r*cos($teta), 
			$r*sin($teta)
		);
		$c->set_s($teta, TRUE);
		return $c;
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
		return $this->arg() + 2*pi()*$this->s;
	}
	
	/*
	 *
	 */
	function get_s ($teta = NULL)
	{
		if ( !is_null($teta) ) {
			$max_s_error = 1e-4;
			$st = ( $teta - $this->arg() )/2/pi();
			$s = (int) round($st);
			if ( (abs($st - $s) > $max_s_error) ) {
				#throw new LogicException('invalid s');
				return FALSE;
			}
			return $s;
		}
		return $this->s;
	}
	
	/*
	 *
	 */
	function set_s ($s, $teta_mode = NULL)
	{
		if ( !empty($teta_mode) ) {
			$teta = $s;
		} else {
			$teta = $this->arg() + 2*pi()*$s;
		}
		$s = $this->get_s($teta);
		if ( !($s === FALSE) ) {
			$this->s = $s;
		}
		return $this->get_s();
	}
	
	/*
	 *
	 */
	function sqrt ($s = NULL)
	{
		if ( is_null($s) ) {
			$s = $this->s;
		}
		$rc = sqrt(($this->abs() + $this->re)/2);
		$ic = (($this->im < 0)? -1 : 1) * sqrt(($this->abs() - $this->re)/2);
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
			$s = $this->s;
		}
		if ( ($this->abs() == 0) ) {
			return new static(0,0,$s);
		}
		$r2 = pow($this->abs(),2);
		$c = new static(
			$this->re/$r2, 
			-$this->im/$r2,
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
			$s = $this->s;
		}
		$c->set_s($s);
		return $c;
	}
	
	/**
	 * @return complex
	 */
	function exp ()
	{
		$c = static::c_polar(
			exp($this->re), 
			$this->im
		);
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
			#return array(c_mult($z, $a->log())->exp());
			$r_v = exp(-$z->Im() * $a->teta());
			$teta_v = $z->Im() * log($a->abs());
			$av = array( 
				0 => static::c_polar($r_v, $teta_v)
			);
			return $av;
		}
		
		if ( is_null($s) ) {
			// return only the k=0 value
			return array(
				0 => static::c_mult($z, $a->log())->exp()
			);
		}

		$c = $z->Re();
		#echo 'c_apow 1/abs(c) ',1/abs($c),"\n";
		#echo 'c_apow s ',$s,"\n";
		$tv = ( $z->Im() * log($a->abs()) + $c * $a->teta() )/(2*pi()); 
	#if ( is_null($s) ) {
	#	$s = (int) ceil( ($tv + 1/2) ) - 1;
	#}
		if ( ( $c > 0 ) ) {
			$k = (int) floor( (-$tv + $s - 1/2)/$c ) + 1;
		} else {
			$k = (int) floor( (-$tv + $s - 1/2)/$c );
		}
		#echo 'c_apow k ',$k,"\n";

		#$r_v = pow($a->abs(), $z->Re()) * exp(-$z->Im() * ( $a->teta() + 2*pi()*$k ));
		#$teta_v = $z->Im() * log($a->abs()) + $z->Re() * ( $a->teta() + 2*pi()*$k );
		#$v = c_polar($r_v, $teta_v);

		if ( ( $c > 0 ) ) {
			$f = (int) floor( (-$tv + $s + 1/2)/$c );
			do {
				$a->set_s($sa+$k);
				$av[$k] = static::c_mult($z, $a->log())->exp();
				$k++;
			} while ( ($k <= $f) );
		} else {
			$f = (int) ceil( (-$tv + $s + 1/2)/$c );
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
			$s = $c->get_s( $a->teta() + $b->teta(), TRUE );
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
			$s = $c->get_s( $a->teta() - $b->teta(), TRUE );
		}
		$c->set_s($s);
		return $c;
	}

	/*
	 *
	 */
	public function __toString ()
	{
		#$format='%13.5g';
		$format='%.5g';
		$str = sprintf($format, $this->re); 
		if ( ($this->re >= 0) ) {
			$str = ' ' . $str;
		}
		$format='%.5g';
		if ( ($this->im > 0) ) {
			$str .= ' + ' . sprintf($format, $this->im) . 'i'; 
		} elseif ( ($this->im < 0) ) {
			$str .= ' - ' . sprintf($format, abs($this->im)) . 'i'; 
		}
		$str .= ' (' . $this->arg()*180/pi() . 'º';
		$str .= (($this->s == 0)? ')' : '(' . $this->s . '))');
		#return str_pad($str,26); 
		return $str; 
	}

// Object oriented style

	/*
	 *
	 */
	/*
	function mult ($b, $s = NULL)
	{
		return static::c_mult($this, $b, $s);
	}
	*/

	/*
	 *
	 */
	function pow ($b, $s = NULL)
	{
		return static::c_pow($this, $b, $s);
	}

}
