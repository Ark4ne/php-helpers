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

		$this->assertEquals($url, url($url));
		$this->assertEquals($url, url($url, [], true));
		$this->assertEquals($url, url($url, [], true, 'www.google.com'));
		$this->assertEquals($url, url($url, [], true, 'www.google.com/'));

		$url_not_secure = 'http://www.google.com/';
		$this->assertEquals($url_not_secure, url($url_not_secure));
		$this->assertEquals($url, url($url_not_secure, [], true));

		$url_not_secure_no_slash = 'http://www.google.com';
		$this->assertEquals($url_not_secure_no_slash . '/', url($url_not_secure_no_slash));
		$this->assertEquals($url, url($url_not_secure_no_slash, [], true));

		$url = 'http://www.google.com/';
		$this->assertEquals($url . '?a=a', url($url_not_secure_no_slash, ['a' => 'a']));
	}
}
