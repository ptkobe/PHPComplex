<?php
/**
* @package PHPComplex
* File Complex.php
*/
namespace enove\PHPComplex;

/* gettext domain */
define('PHPCOMPLEX_DOMAIN','phpcomplex');

/**
 * Class Complex
 */
class Complex implements iComplex
{
	/**
	 * int Mode.
	 */
	protected $mode = self::PHPCOMPLEX_MODE_COORDS;

	/**
	 * float Real part.
	 */
	protected $re;

	/**
	 * float Imaginary part.
	 */
	protected $im;

	/**
	 * int Branch.
	 */
	protected $s;
	
	/**
	 * float Abs.
	 */
	protected $r;

	/**
	 * float Unitary Angle.
	 */
	protected $theta;


	/**
	 * Complex construct.
	 * 
	 * @param float (Optional) Real part/Radius.
	 * @param float (Optional) Imaginary part/Angle.
	 * @param mixed (Optional) Branch/unitary flag.
	 * @param bool  (Optional) Mode (Coords/Polar).
	 * 
	 * @return object Complex
	 * @throws 
	 */
	public function __construct($x = NULL, $y = NULL, $s = NULL, $mode = NULL)
	{
		if ( is_null($mode) ) {
			$mode = self::PHPCOMPLEX_MODE_COORDS;
		}

		if ( ($mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_COORDS, FALSE);
			if ( is_null($x) ) {
				$x = 0;
			}
			if ( is_null($y) ) {
				$y = 0;
			}
			if ( is_null($s) ) {
				$s = 0;
			}
			$this->set_coords($x, $y, $s);
		} elseif  ( ($mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_POLAR, FALSE);
			if ( is_null($x) ) {
				$x = 0;
			}
			if ( is_null($y) ) {
				$y = 0;
			}
			$this->set_polar($x, $y, $s);
		} else {
			trigger_error(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid mode'),
				E_USER_ERROR
			);
		}
	}

	/**
	 * Creates a polar complex from radius and angle.
	 * Note: Arg will be set to ]-pi(),pi()], and branch will be set.
	 * 
	 * @param float Radius.
	 * @param float (Optional) Angle. Default: 0.
	 * @param bool  (Optional) unitary flag. Default: FALSE.
	 * @return object Complex
	 */
	static public function c_polar($r, $theta = NULL, $umode = NULL)
	{
		return new static($r, $theta, $umode, self::PHPCOMPLEX_MODE_POLAR);
	}

	/**
	 * Creates a polar complex from radius and unitary angle.
	 * Note: uArg will be set to ]-0.5,0.5], and branch will be set.
	 * 
	 * @param float Radius.
	 * @param float (Optional) Angle as unitary.
	 * @return object Complex
	 */
	static public function c_upolar($r, $utheta = NULL)
	{
		return static::c_polar($r, $utheta, TRUE);
	}

	/**
	 * Creates a complex from an array.
	 * 
	 * @param array An array( Re, Im [, branch] )
	 * @param int   (Optional) Branch.
	 * @return object Complex.
	 */
	static public function atoc($a, $s = NULL) {
		if ( isset($a[2]) && is_null($s) ) {
			$s = $a[2];
		}
		$c = new static($a[0], $a[1], $s);
		return $c;
	}


	/**
	 * Updates complex with plane coordinates.
	 * 
	 * @param float (Optional) Real part.
	 * @param float (Optional) Imaginary part.
	 * @param int   (Optional) Branch.
	 * @return object Complex.
	 */
	public function set_coords($x = NULL, $y = NULL, $s = NULL)
	{
		$this->set_mode(self::PHPCOMPLEX_MODE_COORDS);

		if ( !is_null($x) ) {
			if ( !is_numeric($x) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			$this->set_Re($x);
		}
		if ( !is_null($y) ) {
			if ( !is_numeric($y) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			$this->set_Im($y);
		}
		if ( !is_null($s) ) {
			if ( !is_int($s) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
				);
			}
			$this->set_branch($s);
		}
		return $this;
	}

	/**
	 * Updates complex with polar coordinates.
	 * 
	 * @return object Complex A
	 */
	public function set_polar($r = NULL, $theta = NULL, $umode = NULL)
	{
		$this->set_mode(self::PHPCOMPLEX_MODE_POLAR);

		if ( !is_null($r) ) {
			if ( !is_numeric($r) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			$this->set_abs($r);
		}
		if ( !is_null($theta) ) {
			if ( !is_numeric($theta) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			if ( ($umode === TRUE) ) {
				$this->set_utheta($theta);
			} else {
				$this->set_theta($theta);
			}
		}
		return $this;
	}


	/**
	 * Get Conjugate.
	 * 
	 * @param int (Optional) Branch for the result.
	 * @return object Complex Re(A)-Im(A)i
	 */
	public function conj($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$c = new static(
				$this->Re(),
				-$this->Im()
			);
			if ( is_null($s) ) {
				$c->set_branch($this->branch());
				$s = $c->uminus_branch();
			}
			$c->set_branch($s);
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$c = static::c_upolar(
				$this->abs(), 
				-$this->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			return NULL;
		}
		return $c;
	}

	/*
	 * Inverse.
	 * 
	 * @param int (Optional) Branch for the result.
	 * @return object Complex 1/A.
	 */
	public function inv($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}

		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
				return 1/0;
			}
			$r2 = $this->Re()*$this->Re() + $this->Im()*$this->Im();
			$c = new static(
				$this->Re()/$r2,
				-$this->Im()/$r2
			);
			if ( is_null($s) ) {
				$c->set_branch($this->branch());
				$s = $c->uminus_branch();
			}
			$c->set_branch($s);
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			if ( ($this->abs() == 0) ) {
				return 1/0;
			}
			$c = static::c_upolar(
				1/$this->abs(), 
				-$this->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			return NULL;
		}
		return $c;
	}

	/*
	 * Minus.
	 * 
	 * @param int (Optional) Branch for the result.
	 * @return object Complex -A.
	 */
	public function uminus($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}

		if ( is_null($s) ) {
			$s = $this->branch();
		}
		$c = new static(
			-$this->Re(),
			-$this->Im(),
			$s
		);
		return $c;
	}

	/**
	 * Square root.
	 * 
	 * @param int (Optional) Branch for the result.
	 * @return object Complex square root of A.
	 */
	public function sqrt($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$r = $this->abs();
			$rc = sqrt( ($r + $this->Re())/2 );
			$ic = sqrt( ($r - $this->Re())/2 );
			
			$theta = $this->theta()/2;
			if ( (cos($theta) < 0) ) {
				$rc = -$rc;
			}
			if ( (sin($theta) < 0) ) {
				$ic = -$ic;
			}
			
			if ( is_null($s) ) {
				$s = $this->branch($theta);
			}
			$c = new static($rc, $ic, $s);

		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$c = static::c_upolar(
				sqrt($this->abs()), 
				$this->utheta()/2
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			return NULL;
		}
		return $c;
	}

	/**
	 * Array Square root.
	 * 
	 * @param int (Optional) Branch for the result.
	 * @return array Array of Complex square roots of A.
	 */
	public function asqrt($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}

		$c = $this->sqrt($s);
		return array(
			$c,
			$c->uminus(),
		);
	}

	/*
	 * Natural logarithm.
	 * 
	 * @param int (Optional) Branch s for the result.
	 * @return object Complex log(A).
	 */
	public function log($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}

		if ( is_null($s) ) {
			$s = $this->branch();
		}
		$c = new static(
			log($this->abs()), 
			$this->theta(),
			$s
		);
		return $c;
	}

	/**
	 * Exponential.
	 * 
	 * @return object Complex e**A.
	 */
	public function exp()
	{
		$c = static::c_polar(
			exp($this->Re()), 
			$this->Im()
		);
		return $c;
	}
	

	/**
	 * Addition.
	 *
	 * @param object Complex A.
	 * @param object Complex B.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex A+B.
	 */
	static public function c_add($a, $b, $s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}

		if ( is_null($s) ) {
			$s = static::ubranch( $a->utheta() );
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
	 * @param object Complex A.
	 * @param object Complex B.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex A-B.
	 */
	static public function c_sub($a, $b, $s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}

		if ( is_null($s) ) {
			$s = static::ubranch( $a->utheta() );
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
	 * @param object Complex A.
	 * @param object Complex B.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex A*B.
	 */
	static public function c_mult($a, $b, $s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}

		if ( ($a->mode === self::PHPCOMPLEX_MODE_COORDS) 
				&& ($a->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$c = static::c_upolar(
				$a->abs()*$b->abs(),
				$a->utheta()+$b->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			if ( is_null($s) ) {
				$s = static::ubranch( $a->utheta() + $b->utheta() );
			}
			$c = new static(
				$a->Re()*$b->Re()-$a->Im()*$b->Im(),
				$a->Im()*$b->Re()+$a->Re()*$b->Im(),
				$s
			);
		}
		return $c;
	}

	/**
	 * Division.
	 *
	 * @param object Complex A.
	 * @param object Complex B.
	 * @param int (Optional) Branch for the result.
	 * @return object Complex A/B.
	 */
	static public function c_div($a, $b, $s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, $s);
		}
		if ( !static::is_complex($b) ) {
			$b = new static($b, NULL, $s);
		}

		if ( ($a->mode === self::PHPCOMPLEX_MODE_COORDS) 
				&& ($a->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			if ( ($b->abs() == 0) ) {
				return 1/0;
			}
			$c = static::c_upolar(
				$a->abs()/$b->abs(),
				$a->utheta()-$b->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			$r2b = $b->Re()*$b->Re() + $b->Im()*$b->Im();
			if ( ($r2b == 0) ) {
				return 1/0;
			}
			if ( is_null($s) ) {
				$s = static::ubranch( $a->utheta() - $b->utheta() );
			}
			$c = new static(
				($a->Re()*$b->Re()+$a->Im()*$b->Im())/$r2b,
				($a->Im()*$b->Re()-$a->Re()*$b->Im())/$r2b,
				$s
			);
		}
		return $c;
	}

	/**
	 * Power.
	 * 
	 * @param object Complex A.
	 * @param object Complex z.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex A ** z.
	 */
	static public function c_pow($a, $z, $s = NULL)
	{
		/*
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, 0);
		}
		if ( !static::is_complex($z) ) {
			$z = new static($z, NULL, 0);
		}
		
		if ( ($a->Re() == 0) && ($a->Im() == 0) ) {
			// 0 ** z = 1
			return new static(1, 0, $s);
		}
		
		#echo 'c_apow Re(z)k ',$z->Re(),"\n";
		if ( ($z->Re() == 0) && ($z->Im() == 0) ) {
			// w ** 0 = 1
			return new static(1, 0, $s);
		}

		if ( ( $z->Re() == 0 ) ) {
			// Only one result
			#return $z->mult($a->log())->exp();
			$r_v = exp(-$z->Im() * $a->theta());
			$theta_v = $z->Im() * log($a->abs());
			return static::c_polar($r_v, $theta_v);
		}
		
		$k = 0;
		if ( !is_null($s) ) {
			$tv = ( $z->Im() * log($a->abs())
					+ $z->Re() * $a->theta() )
					/(2*pi()); 
			if ( ( $z->Re() > 0 ) ) {
				$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() ) + 1;
			} else {
				$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() );
			}
		}

		$r_v = pow($a->abs(), $z->Re()) 
				* exp(-$z->Im() * ( $a->theta() + 2*pi()*$k ));
		$theta_v = $z->Im() * log($a->abs())
				 + $z->Re() * ( $a->theta() + 2*pi()*$k );
		#return static::c_polar($r_v, $theta_v);

		if ( !is_null($s) ) {
			$w = clone($a);
			$w->set_branch($w->branch()+$k);
			return $z->mult($w->log())->exp();
		} 
		return $z->mult($a->log())->exp();
		*/

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
		
		return $av[$key];
		
		/*
		// if $av was not ordered
		reset($av);
		list($key, $v) = each($av);
		$p = $v->arg();
		while ( (list($k, $v) = each($av)) ) {
			if ( ( ($p < 0) || ($v->arg() >= 0) ) 
			 		&& (abs($v->arg()) < abs($p)) ) {
				$key = $k;
				$p = $v->arg();
			}
		}
		*/
	}

	/**
	 * An array of solutions of w ** z on Branch s.
	 * 
	 * @param object Complex A.
	 * @param object Complex z.
	 * @param int    (Optional) Branch for the result.
	 *     If branch is null, return array( 0 => c_pow(A,z) ).
	 * @return array Array of solutions of A ** z.
	 */
	static public function c_apow($w, $z, $s = NULL)
	{
		$max_roots = 20;
		
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}
		if ( !static::is_complex($w) ) {
			$w = new static($w, NULL, 0);
		}
		if ( !static::is_complex($z) ) {
			$z = new static($z, NULL, 0);
		}
		
		$a = clone($w);
		$sa = $a->branch();
		#echo 'c_apow a ',$a,"\n";
		
		#$r_v = pow($a->abs(), $z->Re()) 
		#		* exp(-$z->Im() * ( $a->theta() + 2*pi()*$k ));
		#$theta_v = $z->Im() * log($a->abs()) + $z->Re() 
		#		* ( $a->theta() + 2*pi()*$k );
		#$v = c_polar($r_v, $theta_v);
		
		if ( ($a->Re() == 0) && ($a->Im() == 0) ) {
			// 0 ** z = 1
			$av = array( 
				0 => new static(1, 0, $s)
			);
			return $av;
		}
		
		#echo 'c_apow Re(z)k ',$z->Re(),"\n";
		if ( ( $z->Re() == 0 ) ) {
			if ( ( $z->Im() == 0 ) ) {
				// w ** 0 = 1
				$av = array( 
					0 => new static(1, 0, $s)
				);
				return $av;
			}
			
			// Only one result
			$r_v = exp(-$z->Im() * $a->theta());
			$theta_v = $z->Im() * log($a->abs());
			$av = array( 
				0 => static::c_polar($r_v, $theta_v)
			);
			return $av;
		}
		
		if ( is_null($s) ) {
			// return only the k=0 value
			$av = array( 
				0 => $z->mult($a->log())->exp(),
			);
			return $av;
		}
		
		#echo 'c_apow 1/abs(c) ',1/abs($z->Re()),"\n";
		#echo 'c_apow s ',$s,"\n";
		
		$tv = ( $z->Im() * log($a->abs())
				+ $z->Re() * $a->theta() )/(2*pi()); 
		if ( ( $z->Re() > 0 ) ) {
			$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() ) + 1;
		} else {
			$k = (int) floor( (-$tv + $s - 1/2)/$z->Re() );
		}
		
		if ( ( $z->Re() > 0 ) ) {
			$f = (int) floor( (-$tv + $s + 1/2)/$z->Re() );
			if ( ($f-$k > $max_roots) ) {
				trigger_error('too many roots... retriving first ' . $max_roots . '.', E_USER_WARNING);
				$f = $k+$max_roots;
			}
			do {
				$a->set_branch($sa+$k);
				$av[$k] = static::c_mult($z, $a->log())->exp();
				$k++;
			} while ( ($k <= $f) );
		} else {
			$f = (int) ceil( (-$tv + $s + 1/2)/$z->Re() );
			if ( ($k-$f > $max_roots) ) {
				trigger_error('too many roots... retriving first 50.', E_USER_WARNING);
				$f = $k-$max_roots;
			}
			do {
				$a->set_branch($sa+$k);
				$av[$k] = static::c_mult($z, $a->log())->exp();
				$k--;
			} while ( ($k >= $f) );
		}
		
		return $av;
	}


	/**
	 * Addition.
	 * 
	 * @param object Complex B.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex this+B.
	 */
	public function add($b, $s = NULL)
	{
		return static::c_add($this, $b, $s);
	}

	/**
	 * Subtraction.
	 *
	 * @param object Complex B.
	 * @param int (Optional) Branch for the result.
	 * @return object Complex this-B.
	 */
	public function sub($b, $s = NULL)
	{
		return static::c_sub($this, $b, $s);
	}

	/**
	 * Multiplication.
	 *
	 * @param object Complex B.
	 * @param int   (Optional) Branch for the result.
	 * @return object Complex this*B.
	 */
	public function mult($b, $s = NULL)
	{
		return static::c_mult($this, $b, $s);
	}

	/**
	 * Division.
	 *
	 * @param object Complex B.
	 * @param int    (Optional) Branch for the result.
	 * @return object Complex this/B.
	 */
	public function div($b, $s = NULL)
	{
		return static::c_div($this, $b, $s);
	}

	/**
	 * Power.
	 * 
	 * @param object Complex z.
	 * @param int (Optional) Branch s for the result.
	 * @return object Complex A**z.
	 */
	public function pow($z, $s = NULL)
	{
		return static::c_pow($this, $z, $s);
	}

	/**
	 * Power as an array of solutions of A ** z.
	 * 
	 * @param object Complex z.
	 * @param int    (Optional) Branch for the result.
	 *     If s is null, return array( 0 => w->pow(z) ).
	 * @return array Array of solutions of this**z.
	 */
	public function apow($z, $s = NULL)
	{
		return static::c_apow($this, $z, $s);
	}

	/**
	 * Flats the complex to a float if possible.
	 * 
	 * @return mixed The float Re() if Im() == 0, or the complex.
	 */
	public function flat()
	{
		if ( ($this->Im() == 0) ) {
			return $this->Re();
		}
		return $this;
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
		$str .= ' (' . rad2deg($this->arg()) . 'º';
		$str .= (($this->branch() == 0)? ')' 
				: '(' . $this->branch() . '))');
		#return str_pad($str,26); 
		return $str; 
	}


	/**
	 * Get store mode.
	 *
	 * @return int Current mode.
	 */
	public function mode()
	{
		return $this->mode;
	}

	/**
	 * Set store mode.
	 *
	 * @param int  New mode.
	 * @param bool (Optional) update flag.
	 *     default: change mode and update values.
	 *     FALSE:   only change mode.
	 * @return void
	 */
	public function set_mode($mode, $update = NULL)
	{
		if ( ($mode === $this->mode) ) {
			return;
		}

		if ( ($update === FALSE) ) {
			if ( ($mode === self::PHPCOMPLEX_MODE_POLAR) ) {
				$this->mode = self::PHPCOMPLEX_MODE_POLAR;
			} elseif  ( ($mode === self::PHPCOMPLEX_MODE_COORDS) ) {
				$this->mode = self::PHPCOMPLEX_MODE_COORDS;
			}
			
		} elseif ( ($mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$abs = $this->abs();
			$theta = $this->utheta();
			$this->mode = self::PHPCOMPLEX_MODE_POLAR;
			$this->set_abs($abs);
			$this->set_utheta($theta);
			
		} elseif  ( ($mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$re = $this->Re();
			$im = $this->Im();
			$s = $this->branch();
			$this->mode = self::PHPCOMPLEX_MODE_COORDS;
			$this->set_Re($re);
			$this->set_Im($im);
			$this->set_branch($s);
		}
	}

	/**
	 * Get Re().
	 * In polar mode, forces zeros.
	 * 
	 * @return float Real part
	 */
	public function Re()
	{
		$precision = 1e-14;
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$x = $this->re;
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$cos = cos($this->theta());
			if ( (abs($cos) < $precision) ) {
				$cos = 0;
			}
			#if ( (abs($cos - 1) < $precision) ) {
			#	$cos = 1;
			#}
			#if ( (abs($cos + 1) < $precision) ) {
			#	$cos = -1;
			#}
			$x = $this->abs()*$cos;
		} else {
			return NULL;
		}
		return $x;
	}

	/**
	 * Set Re().
	 *
	 * @param float Real part.
	 * @return void
	 */
	public function set_Re($x)
	{
		if ( !is_numeric($x) ) {
			return FALSE;
		}

		if  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_COORDS);
		}
		
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->re = $x;
		}
	}

	/**
	 * Get Im().
	 * In polar mode, forces zeros.
	 * 
	 * @return float Imaginary part
	 */
	public function Im()
	{
		$precision = 1e-14;
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$y = $this->im;
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$sin = sin($this->theta());
			if ( (abs($sin) < $precision) ) {
				$sin = 0;
			}
			#if ( (abs($sin - 1) < $precision) ) {
			#	$sin = 1;
			#}
			#if ( (abs($sin + 1) < $precision) ) {
			#	$sin = -1;
			#}
			$y = $this->abs()*$sin;
		} else {
			return NULL;
		}
		return $y;
	}

	/**
	 * Set Im().
	 *
	 * @param float Imaginary part.
	 * @return void
	 */
	public function set_Im($x)
	{
		if ( !is_numeric($x) ) {
			return FALSE;
		}

		if  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_COORDS);
		}
		
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->im = $x;
		}
	}

	/**
	 * Set Absolute/Radius.
	 * 
	 * @param float New |A|.
	 * @return void
	 */
	public function set_abs($x)
	{
		if ( !is_numeric($x) ) {
			return FALSE;
		}
		
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_POLAR);
		}
		
		if  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$this->abs = $x;
		}
	}

	/**
	 * Get Arg.
	 * 
	 * @return float Current Arg
	 */
	public function arg()
	{
		return 2*pi()*$this->uarg();
	}
	/**
	 * Get unitary Arg.
	 * 
	 * @return float Current Arg as unitary.
	 */
	public function uarg()
	{
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
				return 0;
			}
			$uarg = atan2($this->Im(), $this->Re())*0.5/pi();
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			if ( ($this->abs() == 0) ) {
				return 0;
			}
			$uarg = $this->utheta() - $this->branch();
		} else {
			return FALSE;
		}
		if ( ($uarg <= -0.5) || ($uarg > 0.5) ) {
			throw new \LogicException(
				dgettext(PHPCOMPLEX_DOMAIN, 'trying to set invalid arg value')
			);
		}
		return $uarg;
	}

	/**
	 * Get Absolute/Radius.
	 * 
	 * @return float |A|.
	 */
	public function abs()
	{
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$r = sqrt($this->Re()*$this->Re() + $this->Im()*$this->Im());
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			$r = $this->abs;
		} else {
			return NULL;
		}
		return $r;
	}

	/**
	 * Get Angle.
	 *
	 * @return float Angle.
	 */
	public function theta()
	{
		return $this->utheta()*2*pi();
	}
	/**
	 * Get unitary Angle.
	 *
	 * @return float Current Angle as unitary.
	 */
	public function utheta()
	{
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
				return 0;
			}
			$utheta = $this->uarg() + $this->branch();
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			if ( ($this->abs() == 0) ) {
				return 0;
			}
			$utheta = $this->theta;
		} else {
			return FALSE;
		}
		return $utheta;
	}
	
	/**
	 * Set Angle.
	 *
	 * @param float New Angle.
	 * @return void
	 */
	public function set_theta($theta)
	{
		return $this->set_utheta($theta*0.5/pi());
	}
	/**
	 * Set Angle from unitary angle.
	 *
	 * @param float Unitary Angle.
	 * @return void
	 */
	public function set_utheta($utheta)
	{
		if ( !is_numeric($utheta) ) {
			return FALSE;
		}
		if ( ($this->mode() === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->set_mode(self::PHPCOMPLEX_MODE_POLAR);
		}
		
		if  ( ($this->mode() === self::PHPCOMPLEX_MODE_POLAR) ) {
			$this->theta = $utheta;
		}
	}

	/**
	 * Get branch from unitary angle.
	 * 
	 * @param float Unitary angle.
	 * @return int Branch of angle.
	 */
	protected function ubranch($utheta)
	{
		if ( !is_numeric($utheta) ) {
			return FALSE;
		}

		if ( ($utheta > -0.5) && ($utheta <= 0.5) ) {
			$s = 0;
		} else {
			$s = (int) ceil( ($utheta - 0.5) );
		}
		return $s;
	}

	/**
	 * Get branch.
	 * 
	 * @param float (Optional) Angle to get branch from.
	 * @return int Current branch or branch of angle.
	 */
	public function branch($theta = NULL)
	{
		if ( !is_null($theta) ) {
			return $this->ubranch($theta*0.5/pi());
		} else {
			if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
				$s = $this->s;
			} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
				#$s = $this->branch($this->theta());
				$s = $this->ubranch($this->utheta());
			} else {
				return NULL;
			}
		}
		return $s;
	}

	/*
	 * Set "branch".
	 *
	 * @param int New branch.
	 * @return void
	 */
	public function set_branch($s)
	{
		if ( !is_int($s) ) {
			return FALSE;
		}
		if ( ($this->mode === self::PHPCOMPLEX_MODE_COORDS) ) {
			$this->s = $s;
		} elseif  ( ($this->mode === self::PHPCOMPLEX_MODE_POLAR) ) {
			#$this->set_theta($this->arg() + 2*pi()*$s);
			$this->set_utheta( 
				$this->utheta() - $this->branch() + $s
			);
		}
	}

	/*
	 * Get the simetrical branch, the branch of -theta.
	 *
	 * @return int Simetrical branch.
	 */
	public function uminus_branch()
	{
		$s = -$this->branch();
		if ( ($this->Im() == 0) && ($this->Re() < 0) ) {
			$s--;
		}
		return $s;
	}


	/**
	 * Checks if object is of the calling class.
	 * 
	 * @param object
	 * @return bool
	 */
	static public function is_complex($a)
	{
		return is_object($a) && (get_class($a) == get_called_class());
	}

	/**
	 * Checks if object is of the calling class or of a subclass.
	 * 
	 * @param object
	 * @return bool
	 */
	static public function is_a_complex($a)
	{
		return is_a($a, get_called_class());
	}


	/**
	 * Checks if Complexs are equal.
	 * 
	 * @param object Complex A.
	 * @param object Complex B.
	 * @param float  precision.
	 * @param bool   strict flag. If TRUE checks branch too.
	 * @return bool
	 */
	static public function is_equal($a, $b, $err = NULL, $strict = NULL)
	{
		if ( is_null($err) ) {
			$err = 1e-12;
		}
		$r = ( abs($a->Re() - $b->Re()) <= $err ) 
				&& ( abs($a->Im() - $b->Im()) <= $err );
		if ( $r && !empty($strict) ) {
			$r = $r && ($a->branch() == $b->branch());
		}
		return $r;
	}

}
