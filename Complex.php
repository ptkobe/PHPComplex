<?php
/**
* @package PHPComplex
* File Complex.php
*/
namespace enove\PHPComplex;

require_once 'iComplex.php';

/* gettext domain */
define('PHPCOMPLEX_DOMAIN','phpcomplex');

if (!defined('PHPCOMPLEX_CLASS')) {
	define('PHPCOMPLEX_CLASS',__NAMESPACE__.'\Complex');
}

/**
 * Class Complex
 */
class Complex implements iComplex
{
	protected $re;
	protected $im;
	protected $s;
	
	/**
	 * Checks if a is a complex of this class.
	 * 
	 * @return boolean
	 */
	static public function is_complex($a)
	{
		return is_object($a) && (get_class($a) == get_called_class());
	}

	/**
	 * Checks if a is a complex of this class or a subclass.
	 * 
	 * @return boolean
	 */
	static public function is_a_complex($a)
	{
		return is_a($a, get_called_class());
	}

	/**
	 * Complex construct.
	 */
	public function __construct($x = NULL, $y = NULL, $s = NULL)
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
		
		// Remember this allows numeric strings.
		if ( !is_numeric($x) || !is_numeric($y) ) {
			throw new InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
			);
		}
		$this->set_Re($x);
		$this->set_Im($y);
		$this->set_s($s);
	}
	
	/**
	 * Creates a complex from polar coordinates.
	 * Note: arg will be changed to ]-pi(),pi()], and s will be set.
	 * 
	 * @return complex
	 */
	static public function polar($r, $theta = NULL)
	{
		if ( is_null($theta) ) {
			$theta = 0;
		}
		$c = new static(
			$r*cos($theta), 
			$r*sin($theta)
		);
		$c->set_s($c->s_theta($theta));
		return $c;
	}
	
	/**
	 * Creates a complex from an array.
	 * @param array a Array( Re, Im [, s] ) -> complex object
	 * 
	 * @return complex
	 */
	static public function atoc($a, $s = NULL) {
		if ( isset($a[2]) && is_null($s) ) {
			$s = $a[2];
		}
		$c = new static($a[0], $a[1], $s);
		return $c;
	}

	/**
	 * Get Re().
	 * 
	 * @return float Real part
	 */
	public function Re()
	{
		return $this->set_Re();
	}

	/**
	 * Get Im().
	 * 
	 * @return float Imaginary part
	 */
	public function Im()
	{
		return $this->set_Im();
	}

	/**
	 * Get argument.
	 * 
	 * @return float
	 */
	public function arg()
	{
		if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
			return 0;
		}
		return atan2($this->Im(), $this->Re());
	}
	
	/**
	 * Get "branch".
	 * 
	 * @return int current s.
	 */
	public function get_s()
	{
		return $this->set_s();
	}
	
	/*
	 * Set "branch".
	 *
	 * @param int s New s.
	 * @return int current s.
	 */
	public function set_s($s = NULL)
	{
		if ( !is_null($s) ) {
			if ( !is_int($s) ) {
				return FALSE;
			}
			$this->s = $s;
		}
		return $this->s;
	}
	
	/**
	 * Get s from angle.
	 * 
	 * @param float Angle theta.
	 * @return int s from theta.
	 */
	public function s_theta($theta)
	{
		$max_s_error = 1e-4;
		$st = ( $theta - $this->arg() )/2/pi();
		$s = (int) round($st);
		if ( (abs($st - $s) > $max_s_error) ) {
			#throw new LogicException('invalid s');
			return FALSE;
		}
		return $s;
	}
	
	/**
	 * Get Angle.
	 *
	 * @return float arg() + 2 pi s.
	 */
	public function theta()
	{
		return $this->arg() + 2*pi()*$this->get_s();
	}
	
	/**
	 * Get Absolute value.
	 * 
	 * @return float |A|.
	 */
	public function abs()
	{
		#return sqrt(pow($this->Re(),2) + pow($this->Im(),2));
		return sqrt($this->Re()*$this->Re() + $this->Im()*$this->Im());
	}

	/**
	 * Get Conjugate.
	 * 
	 * @return complex Complex Re(A)-Im(A)i
	 */
	public function conj()
	{
		return new static($this->Re(), -$this->Im(), $this->get_s());
	}

	/**
	 * Flats the complex to a float if possible.
	 * 
	 * @return mixed A float value if Im == 0, or the complex.
	 */
	public function flat()
	{
		if ( ($this->Im() == 0) ) {
			return $this->Re();
		}
		return $this;
	}

	/**
	 * Addition.
	 * 
	 * @param complex b
	 * @return complex A+b.
	 */
	public function add($b, $s = NULL)
	{
		return static::c_add($this, $b, $s);
	}

	/**
	 * Subtraction.
	 *
	 * @param complex b
	 * @return complex A-b.
	 */
	public function sub($b, $s = NULL)
	{
		return static::c_sub($this, $b, $s);
	}

	/**
	 * Multiplication.
	 *
	 * @param complex b
	 * @return complex A*b.
	 */
	public function mult($b, $s = NULL)
	{
		return static::c_mult($this, $b, $s);
	}

	/**
	 * Division.
	 *
	 * @param complex b
	 * @return complex A/b.
	 */
	public function div($b, $s = NULL)
	{
		return static::c_div($this, $b, $s);
	}

	/**
	 * Power.
	 * 
	 * @param complex z
	 * @return complex A ** z.
	 */
	public function pow($z, $s = NULL)
	{
		return static::c_pow($this, $z, $s);
	}

	/**
	 * Power as an array of solutions of A ** z.
	 * 
	 * @param complex z
	 * @return array Array of solutions of A ** z.
	 */
	public function apow($z, $s = NULL)
	{
		return static::c_apow($this, $z, $s);
	}

	/**
	 * Square root.
	 * 
	 * @return array Array of roots.
	 */
	public function sqrt($s = NULL)
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
	 * Inverse.
	 * 
	 * @return complex 1/A.
	 */
	public function inv($s = NULL)
	{
		if ( is_null($s) ) {
			$s = $this->get_s();
		}
		if ( ($this->abs() == 0) ) {
			return new static(0,0,$s);
		}
		#$r2 = pow($this->abs(),2);
		$r2 = $this->Re()*$this->Re() + $this->Im()*$this->Im();
		$c = new static(
			$this->Re()/$r2, 
			-$this->Im()/$r2,
			$s
		);
		return $c;
	}

	/*
	 * Natural logarithm.
	 * 
	 * @return complex log(A).
	 */
	public function log($s = NULL)
	{
		$c = new static(
			log($this->abs()), 
			$this->theta()
		);
		if ( is_null($s) ) {
			$s = $this->get_s();
		}
		$c->set_s($s);
		return $c;
	}

	/**
	 * Exponential.
	 * 
	 * @return complex e**A.
	 */
	public function exp()
	{
		$c = static::polar(
			exp($this->Re()), 
			$this->Im()
		);
		return $c;
	}
	

	/**
	 * Addition.
	 *
	 * @param complex a
	 * @param complex b
	 * @return complex a+b.
	 */
	static public function c_add($a, $b, $s = NULL)
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

	/**
	 * Subtraction.
	 *
	 * @param complex a
	 * @param complex b
	 * @return complex a-b.
	 */
	static public function c_sub($a, $b, $s = NULL)
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

	/**
	 * Multiplication.
	 *
	 * @param complex a
	 * @param complex b
	 * @return complex a*b.
	 */
	static public function c_mult($a, $b, $s = NULL)
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
			$s = $c->s_theta( $a->theta() + $b->theta() );
		}
		$c->set_s($s);
		return $c;
	}

	/**
	 * Division.
	 *
	 * @param complex a
	 * @param complex b
	 * @return complex a/b.
	 */
	static public function c_div($a, $b, $s = NULL)
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
			$s = $c->s_theta( $a->theta() - $b->theta() );
		}
		$c->set_s($s);
		return $c;
	}

	/**
	 * Power.
	 * 
	 * @param complex a
	 * @param complex z
	 * @return complex a ** z.
	 */
	static public function c_pow($a, $z, $s = NULL)
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
	 * Power as an array of solutions of w ** z.
	 * 
	 * @param complex w
	 * @param complex z
	 * @return array Array of solutions of w ** z.
	 */
	static public function c_apow($w, $z, $s = NULL)
	{
		$a = clone($w);
		$sa = $a->get_s();
		#echo 'c_apow a ',$a,"\n";

		#$r_v = pow($a->abs(), $z->Re()) * exp(-$z->Im() * ( $a->theta() + 2*pi()*$k ));
		#$theta_v = $z->Im() * log($a->abs()) + $z->Re() * ( $a->theta() + 2*pi()*$k );
		#$v = c_polar($r_v, $theta_v);

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
			
			$r_v = exp(-$z->Im() * $a->theta());
			$theta_v = $z->Im() * log($a->abs());
			$av = array( 
				0 => static::polar($r_v, $theta_v)
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

		$tv = ( $z->Im() * log($a->abs()) + $z->Re() * $a->theta() )/(2*pi()); 
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
	
	/**
	 * __toString()
	 * 
	 * @return string
	 */
	public function __toString()
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

	/**
	 * Set Re().
	 *
	 * @param float x
	 * @return float Actual Re().
	 */
	protected function set_Re($x = NULL)
	{
		if ( is_numeric($x) ) {
			$this->re = $x;
		}
		return $this->re;
	}

	/**
	 * Set Im().
	 *
	 * @param float x
	 * @return float Actual Im().
	 */
	protected function set_Im($x = NULL)
	{
		if ( is_numeric($x) ) {
			$this->im = $x;
		}
		return $this->im;
	}
}
