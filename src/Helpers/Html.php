<?php
/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 09/07/2015
 * Time: 14:46
 */

namespace Ark4ne\Helpers;

class Html
{

	private static $instance;

	private function __construct()
	{
	}

	public static function jsonAttr($attribute, $value)
	{
		return $attribute . "='" . self::jsonValue($value) . "'";
	}

	public static function jsonValue($value)
	{
		return self::instance()->jsonToStringForAttr($value);
	}

	function jsonToStringForAttr($object)
	{
		return $this->dataToStringForAttr(json_encode($object));
	}

	function dataToStringForAttr($string)
	{
		return str_replace("'", '\u2019', $string);
	}

	private static function instance()
	{
		if (self::$instance == null) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function arrayAttr($attribute, array $array, $delimiter = ',')
	{
		return $attribute . "='" . self::arrayValue($array, $delimiter) . "'";
	}

	public static function arrayValue(array $array, $delimiter = ',')
	{
		return self::instance()->arrayToStringForAttr($array, $delimiter);
	}

	function arrayToStringForAttr(array $array, $delimiter = ',')
	{
		return $this->dataToStringForAttr(implode($delimiter, $array));
	}
}