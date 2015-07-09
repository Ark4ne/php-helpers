<?php

/**
 * Created by PhpStorm.
 * User: Guillaume
 * Date: 09/07/2015
 * Time: 03:22
 */
class HelpersFunctionsTest extends PHPUnit_Framework_TestCase
{
	public function testUrl()
	{
		$url = 'https://www.google.com/';

		$this->assertEquals($url, _url($url));
		$this->assertEquals($url, _url($url, [], true));
		$this->assertEquals($url, _url($url, [], true, 'www.google.com'));
		$this->assertEquals($url, _url($url, [], true, 'www.google.com/'));

		$url_not_secure = 'http://www.google.com/';
		$this->assertEquals($url_not_secure, _url($url_not_secure));
		$this->assertEquals($url, _url($url_not_secure, [], true));

		$url_not_secure_no_slash = 'http://www.google.com';
		$this->assertEquals($url_not_secure_no_slash . '/', _url($url_not_secure_no_slash));
		$this->assertEquals($url, _url($url_not_secure_no_slash, [], true));

		$url = 'http://www.google.com/';
		$this->assertEquals($url . '?a=a', _url($url_not_secure_no_slash, ['a' => 'a']));
	}
}
