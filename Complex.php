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
	protected $mode;

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
	 * Complex string output format function. 
	 * Takes the object as argument. Defaults to $this->toString().
	 */
	static protected $format_call = NULL;


	/**
	 * Complex construct.
	 * 
	 * @param float (Optional) Real part/Radius.
	 * @param float (Optional) Imaginary part/Angle.
	 * @param mixed (Optional) Branch/unitary flag.
	 * @param bool  (Optional) Mode (Coords/Polar).
	 * 
	 * @return object Complex
	 * @throws \InvalidArgumentException
	 */
	public function __construct($x = NULL, $y = NULL, $s = NULL, $mode = NULL)
	{
		if ( is_null($mode) ) {
			$mode = self::COORDS;
		}

		if ( ($mode === self::COORDS) ) {
			$this->set_mode(self::COORDS, FALSE);
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
		} elseif  ( ($mode === self::POLAR) ) {
			$this->set_mode(self::POLAR, FALSE);
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
	 * Creates a complex from plane coordinates.
	 * 
	 * @param float (Optional) Real part. Default: 0.
	 * @param float (Optional) Imaginary part. Default: 0.
	 * @param int   (Optional) Branch. Default: 0.
	 * @return object Complex
	 * @throws \InvalidArgumentException
	 */
	static public function c_coords($x = NULL, $y = NULL, $s = NULL)
	{
		return new static($x, $y, $s, self::COORDS);
	}

	/**
	 * Creates a polar complex from radius and angle.
	 * Note: Arg will be set to ]-pi(),pi()], and branch will be set.
	 * 
	 * @param float (Optional) Radius. Default: 0.
	 * @param float (Optional) Angle. Default: 0.
	 * @param bool  (Optional) unitary flag. Default: FALSE.
	 * @return object Complex
	 * @throws \InvalidArgumentException
	 */
	static public function c_polar($r = NULL, $theta = NULL, $umode = NULL)
	{
		return new static($r, $theta, $umode, self::POLAR);
	}

	/**
	 * Creates a polar complex from radius and unitary angle.
	 * An unitary angle is the angle in radians divided by (2*pi()).
	 * 
	 * @param float (Optional) Radius. Default: 0.
	 * @param float (Optional) Angle as unitary. Default: 0.
	 * @return object Complex
	 * @throws \InvalidArgumentException
	 */
	static public function c_upolar($r = NULL, $utheta = NULL)
	{
		return static::c_polar($r, $utheta, TRUE);
	}

	/**
	 * Creates a complex from an array.
	 * 
	 * @param array An array( Re, Im [, branch] )
	 * @param int   (Optional) Branch.
	 * @return object Complex.
	 * @throws \InvalidArgumentException
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
	 * @throws \InvalidArgumentException
	 */
	public function set_coords($x = NULL, $y = NULL, $s = NULL)
	{
		$this->set_mode(self::COORDS);

		if ( !is_null($x) ) {
			if ( !is_numeric($x) || !is_finite($x) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			$this->set_Re($x);
		}
		if ( !is_null($y) ) {
			if ( !is_numeric($y) || !is_finite($y) ) {
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
	 * @param float (Optional) Radius.
	 * @param float (Optional) Angle.
	 * @param bool  (Optional) unitary flag. Default: FALSE.
	 * @return object Complex A
	 * @throws \InvalidArgumentException
	 */
	public function set_polar($r = NULL, $theta = NULL, $umode = NULL)
	{
		$this->set_mode(self::POLAR);

		if ( !is_null($r) ) {
			if ( !is_numeric($r) || !is_finite($r) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'invalid argument')
				);
			}
			$this->set_abs($r);
		}
		if ( !is_null($theta) ) {
			if ( !is_numeric($theta) || !is_finite($theta) ) {
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
		
		if ( ($this->mode() === self::COORDS) ) {
			$c = new static(
				$this->Re(),
				-$this->Im()
			);
			if ( is_null($s) ) {
				$s = $this->uminus_branch();
			}
			$c->set_branch($s);
		} elseif  ( ($this->mode() === self::POLAR) ) {
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
		$c = $this->conj($s);
		if ( ($this->mode() === self::COORDS) ) {
			$r2 = $this->abs2();
			if ( !is_finite(1/$r2) ) {
				return 1/0;
			}
			$c->set_Re($c->Re()/$r2);
			$c->set_Im($c->Im()/$r2);
		} elseif  ( ($this->mode() === self::POLAR) ) {
			if ( !is_finite(1/$this->abs()) ) {
				return 1/0;
			}
			$c->set_abs(1/$this->abs()); 
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
		if ( ($this->mode() === self::COORDS) ) {
			$c = new static(
				-$this->Re(),
				-$this->Im(),
				$s
			);
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$c = static::c_upolar(
				$this->abs(), 
				$this->utheta()+0.5
			);
			$c->set_branch($s);
		} else {
			return NULL;
		}
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
		
		if ( ($this->mode() === self::COORDS) ) {
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
				$s = static::branch_of($theta);
			}
			$c = new static($rc, $ic, $s);

		} elseif  ( ($this->mode() === self::POLAR) ) {
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
	 * @throws \DomainException Logarithm of zero.
	 */
	public function log($s = NULL)
	{
		if ( !is_null($s) && !is_int($s) ) {
			throw new \InvalidArgumentException(
				dgettext(PHPCOMPLEX_DOMAIN, 'invalid branch')
			);
		}

		if ( is_null($s) ) {
			$s = 0;
		}
		try {
			$c = new static(
				0.5*log($this->abs2()), 
				$this->theta(),
				$s
			);
		} catch (\Exception $e) {
			try {
				$c = new static(
					log($this->abs()), 
					$this->theta(),
					$s
				);
			} catch (\Exception $e) {
				throw new \DomainException(
					dgettext(PHPCOMPLEX_DOMAIN, 'logarithm of zero')
				);
			}
		}
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
	 * @throws \InvalidArgumentException
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
			$s = $a->branch();
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
	 * @throws \InvalidArgumentException
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
			$s = $a->branch();
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
	 * @throws \InvalidArgumentException
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

		if ( ($a->mode() === self::COORDS) 
				&& ($a->mode() === self::POLAR) ) {
			$c = static::c_upolar(
				$a->abs()*$b->abs(),
				$a->utheta() + $b->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			if ( is_null($s) ) {
				$s = static::branch_ofu( $a->utheta() + $b->utheta() );
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
	 * @throws \InvalidArgumentException
	 * @throws \DomainException Divide by zero.
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

		if ( ($a->mode() === self::COORDS) 
				&& ($a->mode() === self::POLAR) ) {
			if ( ($b->abs() == 0) ) {
				throw new \DomainException(
					dgettext(PHPCOMPLEX_DOMAIN, 'divide by zero')
				);
				#return 1/0;
			}
			$c = static::c_upolar(
				$a->abs()/$b->abs(),
				$a->utheta() - $b->utheta()
			);
			if ( !is_null($s) ) {
				$c->set_branch($s);
			}
		} else {
			$r2b = $b->abs2();
			if ( ($r2b == 0) ) {
				throw new \InvalidArgumentException(
					dgettext(PHPCOMPLEX_DOMAIN, 'divide by zero')
				);
				#return 1/0;
			}
			if ( is_null($s) ) {
				$s = static::branch_ofu( $a->utheta() - $b->utheta() );
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
	 * @return object Complex A ** z.
	 * @throws \InvalidArgumentException
	 */
	static public function c_pow($a, $z)
	{
		
		if ( !static::is_complex($a) ) {
			$a = new static($a, NULL, 0);
		}
		if ( !static::is_complex($z) ) {
			$z = new static($z, NULL, 0);
		}
		
		if ( ($z->Re() == 0) && ($z->Im() == 0) ) {
			// w ** 0 = 1, 0 ** 0 = 1
			return new static(1, 0, 0);
		}
		if ( ($a->Re() == 0) && ($a->Im() == 0) ) {
			// 0 ** z = 0
			return new static(0, 0, 0);
		}
		try {
			$c = $a->log();
		} catch (\Exception $e) {
			// 0 ** z = 0
			return new static(0, 0, 0);
		}

		return $z->mult($c)->exp();
	}

	/**
	 * An array of solutions of w ** z on Branch s.
	 * 
	 * @param object Complex A.
	 * @param object Complex z.
	 * @param int    (Optional) Branch for the result.
	 *     If branch is null, return array( 0 => c_pow(A,z) ).
	 * @return array Array of solutions of A ** z.
	 * @throws \InvalidArgumentException
	 */
	static public function c_apow($w, $z, $s = NULL)
	{
		$max_roots = 20;
		
		#$r_v = pow($a->abs(), $z->Re()) 
		#		* exp(-$z->Im() * ( $a->theta() + 2*pi()*$k ));
		#$theta_v = $z->Im() * log($a->abs()) 
		#		+ $z->Re() * ( $a->theta() + 2*pi()*$k );
		#$v = c_polar($r_v, $theta_v);
		
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
		
		$av = array();
		if ( ($z->Re() == 0) && ($z->Im() == 0) ) {
			// w ** 0 = 1, 0 ** 0 = 1
			$av[] = new static(1, 0, 0);
			return $av;
		}
		if ( ($a->Re() == 0) && ($a->Im() == 0) ) {
			// 0 ** z = 0
			$av[] = new static(0, 0, 0);
			return $av;
		}
		try {
			$c = $a->log();
		} catch (\Exception $e) {
			// 0 ** z = 0
			return new static(0, 0, 0);
		}
		
		if ( is_null($s) ) {
			$s = static::branch_of(
				$z->Re()*$a->theta() + $z->Im()*log($a->abs())
			); 
		}
		
		if ( ( $z->Re() == 0 ) ) {
			#if ( ( $z->Im() == 0 ) ) {
			#	// w ** 0 = 1
			#	$av = array( 
			#		0 => new static(1, 0, $s)
			#	);
			#	return $av;
			#}
			
			// Only one result
			$r_v = exp(-$z->Im() * $a->theta());
			$theta_v = $z->Im() * log($a->abs());
			if ( (static::branch_of($theta_v) == $s) ) {
				$av[] = static::c_polar($r_v, $theta_v);
			}
			return $av;
		}
		
		$ks = -$a->utheta()
			+(-$z->Im()*log($a->abs())*0.5/pi()+$s)/$z->Re()
		; 
		if ( ( $z->Re() > 0 ) ) {
			// k > x
			$k = (int) floor( $ks - 0.5/$z->Re() ) + 1;
			// f <= x
			$f = (int) floor( $ks + 0.5/$z->Re() );
			if ( ($f-$k > $max_roots) ) {
				trigger_error('too many roots... retriving first ' . $max_roots . '.', E_USER_WARNING);
				$f = $k+$max_roots;
			}
			do {
				$a->set_branch($sa+$k);
				$av[$k] = $z->mult($a->log())->exp();
				$k++;
			} while ( ($k <= $f) );
		} else {
			// k < x
			$k = (int) ceil( $ks - 0.5/$z->Re() ) - 1;
			// f >= x
			$f = (int) ceil( $ks + 0.5/$z->Re() );
			if ( ($k-$f > $max_roots) ) {
				trigger_error('too many roots... retriving first ' . $max_roots . '.', E_USER_WARNING);
				$f = $k-$max_roots;
			}
			do {
				$a->set_branch($sa+$k);
				$av[$k] = $z->mult($a->log())->exp();
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
	 * @throws \InvalidArgumentException
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
	 * @throws \InvalidArgumentException
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
	 * @throws \InvalidArgumentException
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
	 * @throws \InvalidArgumentException
	 * @throws \DomainException Divide by zero.
	 */
	public function div($b, $s = NULL)
	{
		return static::c_div($this, $b, $s);
	}

	/**
	 * Power.
	 * 
	 * @param object Complex z.
	 * @return object Complex A**z.
	 * @throws \InvalidArgumentException
	 */
	public function pow($z)
	{
		return static::c_pow($this, $z);
	}

	/**
	 * Power as an array of solutions of A ** z.
	 * 
	 * @param object Complex z.
	 * @param int    (Optional) Branch for the result.
	 *     If s is null, return array( 0 => w->pow(z) ).
	 * @return array Array of solutions of this**z.
	 * @throws \InvalidArgumentException
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
			if ( ($mode === self::POLAR) ) {
				$this->mode = self::POLAR;
			} elseif  ( ($mode === self::COORDS) ) {
				$this->mode = self::COORDS;
			}
			
		} elseif ( ($mode === self::POLAR) ) {
			$abs = $this->abs();
			$theta = $this->utheta();
			$this->mode = self::POLAR;
			$this->set_abs($abs);
			$this->set_utheta($theta);
			
		} elseif  ( ($mode === self::COORDS) ) {
			$re = $this->Re();
			$im = $this->Im();
			$s = $this->branch();
			$this->mode = self::COORDS;
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
		if ( ($this->mode() === self::COORDS) ) {
			$x = $this->re;
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$cos = cos($this->theta());
			if ( (abs($cos) < $precision) ) {
				$cos = 0;
			}
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
	 * @return void or FALSE on invalid argument
	 */
	public function set_Re($x)
	{
		if ( !is_numeric($x) || !is_finite($x) ) {
			return FALSE;
		}

		if  ( ($this->mode() === self::POLAR) ) {
			$this->set_mode(self::COORDS);
		}
		
		if ( ($this->mode() === self::COORDS) ) {
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
		if ( ($this->mode() === self::COORDS) ) {
			$y = $this->im;
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$sin = sin($this->theta());
			if ( (abs($sin) < $precision) ) {
				$sin = 0;
			}
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
	 * @return void or FALSE on invalid argument
	 */
	public function set_Im($x)
	{
		if ( !is_numeric($x) || !is_finite($x) ) {
			return FALSE;
		}

		if  ( ($this->mode() === self::POLAR) ) {
			$this->set_mode(self::COORDS);
		}
		
		if ( ($this->mode() === self::COORDS) ) {
			$this->im = $x;
		}
	}

	/**
	 * Get Absolute/Radius.
	 * 
	 * @return float |A|.
	 */
	public function abs()
	{
		if ( ($this->mode() === self::COORDS) ) {
			if ( ($this->Re() == 0) ) {
				$r = abs($this->Im());
			} elseif ( ($this->Im() == 0) ) {
				$r = abs($this->Re());
			} else {
				$r = sqrt(
					$this->Re()*$this->Re() 
					+ $this->Im()*$this->Im()
				);
			}
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$r = $this->r;
		} else {
			return NULL;
		}
		return $r;
	}

	/**
	 * Get Square of Absolute/Radius.
	 * 
	 * @return float |A|**2.
	 */
	public function abs2()
	{
		if ( ($this->mode() === self::COORDS) ) {
			if ( ($this->Re() == 0) ) {
				$r = $this->Im()*$this->Im();
			} elseif ( ($this->Im() == 0) ) {
				$r = $this->Re()*$this->Re();
			} else {
				$r = $this->Re()*$this->Re() 
						+ $this->Im()*$this->Im();
			}
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$r = $this->r*$this->r;
		} else {
			return NULL;
		}
		return $r;
	}

	/**
	 * Set Absolute/Radius.
	 * 
	 * @param float New |A|.
	 * @return void or FALSE on invalid argument
	 */
	public function set_abs($x)
	{
		if ( !is_numeric($x) || !is_finite($x) ) {
			return FALSE;
		}
		
		if ( ($this->mode() === self::COORDS) ) {
			$this->set_mode(self::POLAR);
		}
		
		if  ( ($this->mode() === self::POLAR) ) {
			$this->r = $x;
		}
	}

	/**
	 * Get Arg.
	 * 
	 * @return float Current Arg
	 */
	public function arg()
	{
		if ( ($this->mode() === self::COORDS) ) {
			if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
				return 0;
			}
			$arg = atan2($this->Im(), $this->Re());
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$arg = 2*pi()*$this->uarg();
		} else {
			return NULL;
		}
		return $arg;
	}
	/**
	 * Get unitary Arg.
	 * 
	 * @return float Current Arg as unitary.
	 */
	public function uarg()
	{
		if ( ($this->mode() === self::COORDS) ) {
			$uarg = $this->arg()*0.5/pi();
		} elseif  ( ($this->mode() === self::POLAR) ) {
			if ( ($this->abs() == 0) ) {
				return 0;
			}
			$uarg = $this->utheta() - $this->branch();
			
			if ( ($uarg <= -0.5) || ($uarg > 0.5) ) {
				throw new \LogicException(
					dgettext(PHPCOMPLEX_DOMAIN, 'trying to set invalid arg value')
				);
			}
		} else {
			return NULL;
		}
		return $uarg;
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
		if ( ($this->mode() === self::COORDS) ) {
			if ( ($this->Re() == 0) && ($this->Im() == 0) ) {
				return 0;
			}
			$utheta = $this->uarg() + $this->branch();
		} elseif  ( ($this->mode() === self::POLAR) ) {
			if ( ($this->abs() == 0) ) {
				return 0;
			}
			$utheta = $this->theta;
		} else {
			return NULL;
		}
		return $utheta;
	}
	
	/**
	 * Set Angle.
	 *
	 * @param float New Angle.
	 * @return void or FALSE on invalid argument
	 */
	public function set_theta($theta)
	{
		return $this->set_utheta($theta*0.5/pi());
	}
	/**
	 * Set Angle from unitary angle.
	 *
	 * @param float Unitary Angle.
	 * @return void or FALSE on invalid argument
	 */
	public function set_utheta($utheta)
	{
		if ( !is_numeric($utheta) || !is_finite($utheta) ) {
			return FALSE;
		}
		if ( ($this->mode() === self::COORDS) ) {
			$this->set_mode(self::POLAR);
		}
		
		if  ( ($this->mode() === self::POLAR) ) {
			$this->theta = $utheta;
		}
	}

	/**
	 * Get branch.
	 * 
	 * @param float (Optional) Angle to get branch from.
	 * @return int Current branch or branch of angle.
	 */
	public function branch()
	{
		if ( ($this->mode() === self::COORDS) ) {
			$s = $this->s;
		} elseif  ( ($this->mode() === self::POLAR) ) {
			$s = $this->branch_ofu($this->utheta());
		} else {
			return NULL;
		}
		return $s;
	}

	/*
	 * Set "branch".
	 *
	 * @param int New branch.
	 * @return void or FALSE on invalid argument
	 */
	public function set_branch($s)
	{
		if ( !is_int($s) ) {
			return FALSE;
		}
		if ( ($this->mode() === self::COORDS) ) {
			$this->s = $s;
		} elseif  ( ($this->mode() === self::POLAR) ) {
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
	 * Get branch of an angle.
	 * 
	 * @param float Angle to get branch from.
	 * @return int branch of angle.
	 */
	static public function branch_of($theta)
	{
		return static::branch_ofu($theta*0.5/pi());
	}

	/**
	 * Get branch from unitary angle.
	 * 
	 * @param float Unitary angle.
	 * @return int Branch of angle.
	 */
	static public function branch_ofu($utheta)
	{
		if ( !is_numeric($utheta) || !is_finite($utheta) ) {
			return FALSE;
		}

		if ( ($utheta > -0.5) && ($utheta <= 0.5) ) {
			$s = 0;
		} else {
			// int s >= x
			$s = (int) ceil( ($utheta - 0.5) );
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
			$r = ( $a->Re() == $b->Re() ) 
					&& ( $a->Im() == $b->Im() );
		}else {
			$r = ( abs($a->Re() - $b->Re()) <= $err ) 
					&& ( abs($a->Im() - $b->Im()) <= $err );
		}
		if ( $r && !empty($strict) ) {
			$r = $r && ($a->branch() == $b->branch());
		}
		return $r;
	}

	/**
	 * Sets the format call for complex string output.
	 * 
	 * @param callable (Optional) A callable 
	 * @return callable The previous function.
	 */
	public static function set_format(callable $format = NULL)
	{
		$old_fmt = self::$format_call;
		self::$format_call = $format;
		return $old_fmt;
	}

	/**
	 * __toString()
	 * 
	 * @return string
	 */
	public function __toString()
	{
		#return f($this);
		
		if ( is_null(self::$format_call) ) {
			$f = function() { return $this->toString(); };
		} else {
			$f = self::$format_call;
		}
		return $f($this);
		
	}
	
	/**
	 * This is the default function called by __toString() if not
	 * otherwise set by format(). 
	 * 
	 * @return string
	 */
	public function toString()
	{
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
		$str .= ($this->branch() == 0)? '' 
				: '(' . $this->branch() . ')';
		return $str; 
	}

}
