<?php
// namespace KWSSO_CORE\Constants;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * KwBaseConstant is an abstract class providing methods to work with constants in child classes.
 */
abstract class KwBaseConstant {

	/**
	 * @var array|null $kwsso_constants_cache An array to cache constants for each child class.
	 */
	private static $kwsso_constants_cache = null;

	/**
	 * Retrieve the constants defined in the child class.
	 *
	 * @return array An array of constants defined in the child class.
	 */
	public static function get_constants() {
		if ( null === self::$kwsso_constants_cache ) {
			self::$kwsso_constants_cache = array();
		}
		$called_class = get_called_class();
		if ( empty( self::$kwsso_constants_cache[ $called_class ] ) ) {
			$reflect                                   = new ReflectionClass( $called_class );
			self::$kwsso_constants_cache[ $called_class ] = $reflect->getConstants();
		}
		return self::$kwsso_constants_cache[ $called_class ];
	}

}
