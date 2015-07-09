<?php

if (!function_exists('object')) {
	/**
	 * Fast way to create object.
	 *
	 * @param array $attributes
	 *
	 * @return object
	 */
	function object(array $attributes = [])
	{
		return (object)$attributes;
	}
}

if (!function_exists('starts_with')) {
	/**
	 * Determine if a given string starts with a given substring.
	 *
	 * @param  string       $haystack
	 * @param  string|array $needles
	 *
	 * @return bool
	 */
	function starts_with($haystack, $needles)
	{
		foreach ((array)$needles as $needle) {
			if ($needle != '' && strpos($haystack, $needle) === 0) {
				return true;
			}
		}

		return false;
	}
}

if (!function_exists('ends_with')) {
	/**
	 * Determine if a given string ends with a given substring.
	 *
	 * @param  string       $haystack
	 * @param  string|array $needles
	 *
	 * @return bool
	 */
	function ends_with($haystack, $needles)
	{
		foreach ((array)$needles as $needle) {
			if ((string)$needle === substr($haystack, -strlen($needle))) {
				return true;
			}
		}

		return false;
	}
}

if (!function_exists('str_contains')) {
	/**
	 * Determine if a given string contains a given substring.
	 *
	 * @param  string       $haystack
	 * @param  string|array $needles
	 *
	 * @return bool
	 */
	function str_contains($haystack, $needles)
	{
		foreach ((array)$needles as $needle) {
			if ($needle != '' && strpos($haystack, $needle) !== false) {
				return true;
			}
		}

		return false;
	}
}

if (!function_exists('json_html_string')) {
	/**
	 * Return json formatted string from $object.
	 *
	 * @param $object
	 *
	 * @return mixed
	 */
	function json_html_string($object)
	{
		return \Ark4ne\Helpers\Html::jsonValue($object);
	}
}

if (!function_exists('json_html_attr')) {
	/**
	 * Return full html attribute with json formatted string from $object.
	 *
	 * @param $attr
	 * @param $object
	 *
	 * @return string
	 */
	function json_html_attr($attr, $object)
	{
		return \Ark4ne\Helpers\Html::jsonAttr($attr, $object);
	}
}

if (!function_exists('_url')) {
	/**
	 * Return formatted url.
	 *
	 * @param string    $to
	 * @param array     $params
	 * @param null|bool $secure
	 * @param null|bool $domain
	 *
	 * @return string
	 */
	function _url($to, $params = [], $secure = null, $domain = null)
	{
		$url = new \Ark4ne\Helpers\URL($to);

		foreach ($params as $key => $value) {
			$url->addParam($key, $value);
		}

		$secure !== null && $url->setSecure($secure);
		$domain !== null && $url->setDomainString($domain);

		return (string)$url;
	}
}
