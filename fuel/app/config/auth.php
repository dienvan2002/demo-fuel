<?php

/**
 * Auth Configuration - Theo chuẩn FuelPHP
 * @see https://fuelphp.com/docs/packages/auth/intro.html
 */
return array(
	/**
	 * -------------------------------------------------------------------------
	 *  The drivers
	 * -------------------------------------------------------------------------
	 * 
	 * Login drivers to load, the first will also be the default returned by Auth::instance().
	 * 
	 */
	'driver' => array('Simpleauth'),

	/**
	 * -------------------------------------------------------------------------
	 *  Set to true to allow multiple logins
	 * -------------------------------------------------------------------------
	 * 
	 * Whether checking for login continues after one driver has validated a login successfully,
	 * this makes it possible to login in multiple ways at the same time.
	 * 
	 */
	'verify_multiple_logins' => false,

	/**
	 * -------------------------------------------------------------------------
	 *  Use your own salt for security reasons
	 * -------------------------------------------------------------------------
	 * 
	 * The salt used for password hashing.
	 * PHẢI KHỚP VỚI login_hash_salt trong simpleauth.php
	 * 
	 */
	'salt' => 'put_some_salt_in_here',

	/**
	 * -------------------------------------------------------------------------
	 *  Password hashing iterations
	 * -------------------------------------------------------------------------
	 * 
	 * The number of iterations(number of "encryptions" made to the password) 
	 * made in the password hashing process(Auth uses PBKDF2). 
	 * More iterations means safer passwords but using more time.
	 * 
	 */
	'iterations' => 10000,
);
