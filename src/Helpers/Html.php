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

	public function dataToStringForAttr($string)
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
}