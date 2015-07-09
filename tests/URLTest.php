<?php

class URLTest extends \PHPUnit_Framework_TestCase
{
	public function testBasicCreation()
	{
		$this->assertEquals('/', new \Ark4ne\Helpers\URL());

		$this->assertEquals('/', new \Ark4ne\Helpers\URL(null));

		$urls = [
			'https://www.google.com/',
			'https://github.com/Ark4ne',
			'http://localhost/',
			'http://localhost/lara-test/home',
		];
		foreach ($urls as $url) {
			$u = new \Ark4ne\Helpers\URL($url);
			$this->assertEquals($url, (string)$u);
		}
	}

	public function testBasicCreationNoProtocol()
	{
		$urls = [
			'www.google.com/',
			'github.com/Ark4ne',
			'localhost/',
			'localhost/lara-test/home',
		];
		foreach ($urls as $url) {
			$u = new \Ark4ne\Helpers\URL($url);
			$this->assertEquals(($u->getDomain() ? 'http://' : '/') . $url, (string)$u);
		}
	}

	public function testBasicCreationNoDomain()
	{
		$urls = [
			'',
			'/Ark4ne/',
			'localhost/',
			'localhost/lara-test/home',
		];
		foreach ($urls as $url) {
			$u = new \Ark4ne\Helpers\URL($url);
			$this->assertEquals((starts_with($url, '/') ? '' : '/') . $url, (string)$u);
		}
	}

	public function testIsSecure()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne/');
		$this->assertFalse($u->isSecure());

		$u = new \Ark4ne\Helpers\URL('www.github.com');
		$this->assertFalse($u->isSecure());

		$u = new \Ark4ne\Helpers\URL('http://www.github.com');
		$this->assertFalse($u->isSecure());

		$u = new \Ark4ne\Helpers\URL('https://www.github.com');
		$this->assertTrue($u->isSecure());
	}

	public function testGetProtocol()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne/');
		$this->assertEquals('', $u->getProtocol());

		$u = new \Ark4ne\Helpers\URL('www.github.com/Ark4ne/');
		$this->assertEquals('', $u->getProtocol());

		$u = new \Ark4ne\Helpers\URL('http://www.github.com/Ark4ne/');
		$this->assertEquals('http', $u->getProtocol());

		$u = new \Ark4ne\Helpers\URL('https://www.github.com/Ark4ne/');
		$this->assertEquals('http', $u->getProtocol());
	}

	public function testGetDomain()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne');
		$this->assertEquals('', $u->getDomain());
		$this->assertEquals('', $u->getDomainString());

		$u = new \Ark4ne\Helpers\URL('www.github.com/Ark4ne');
		$this->assertEquals('www.github.com', $u->getDomain());
		$this->assertEquals('www.github.com/', $u->getDomainString());

		$u = new \Ark4ne\Helpers\URL('https://getcomposer.org/doc/00-intro.md');
		$this->assertEquals('getcomposer.org', $u->getDomain());
		$this->assertEquals('getcomposer.org/', $u->getDomainString());
	}

	public function testGetPage()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne');
		$this->assertEquals('Ark4ne', $u->getPage());
		$this->assertEquals('/Ark4ne', (string)$u);

		$u = new \Ark4ne\Helpers\URL('www.github.com/Ark4ne');
		$this->assertEquals('Ark4ne', $u->getPage());

		$u = new \Ark4ne\Helpers\URL('https://getcomposer.org/doc/00-intro.md');
		$this->assertEquals('doc/00-intro.md', $u->getPage());
	}

	public function testGetParams()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne');
		$this->assertEquals([], $u->getParams());
		$this->assertEquals('', $u->getParamsString());

		$u = new \Ark4ne\Helpers\URL('/Ark4ne?q=test');
		$this->assertEquals(['q' => 'test'], $u->getParams());
		$this->assertEquals('?q=test', $u->getParamsString());

		$u = new \Ark4ne\Helpers\URL('/Ark4ne?q[]=test&q[]=test1&q[]=test2&q[]=test3');
		$this->assertEquals(['q' => ['test', 'test1', 'test2', 'test3']], $u->getParams());
		$this->assertEquals('?q[]=test&q[]=test1&q[]=test2&q[]=test3', $u->getParamsString());

		$u = new \Ark4ne\Helpers\URL('/Ark4ne?q[]=test&amp;q[]=test1&amp;q[]=test2&amp;q[]=test3');
		$this->assertEquals('?q[]=test&q[]=test1&q[]=test2&q[]=test3', $u->getParamsString());

		$u = new \Ark4ne\Helpers\URL('/Ark4ne?q');
		$this->assertEquals('?q=', $u->getParamsString());
	}

	public function testGetAnchor()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne#Test');
		$this->assertEquals('Test', $u->getAnchor());
		$this->assertEquals('#Test', $u->getAnchorString());
	}

	public function testAddQuery()
	{
		$u = new \Ark4ne\Helpers\URL('/Ark4ne?a=a&b[]=b');
		$u->addParam('a', 'z');
		$this->assertEquals([
								'a' => 'z',
								'b' => ['b']
							],
							$u->getParams());
		$this->assertEquals('?a=z&b[]=b', $u->getParamsString());
		$u->addParam('c', 'c');
		$this->assertEquals([
								'a' => 'z',
								'b' => ['b'],
								'c' => 'c'
							],
							$u->getParams());
		$this->assertEquals('?a=z&b[]=b&c=c', $u->getParamsString());

		$u->addParam('b', 'b1');
		$this->assertEquals([
								'a' => 'z',
								'b' => ['b', 'b1'],
								'c' => 'c'
							],
							$u->getParams());
		$this->assertEquals('?a=z&b[]=b&b[]=b1&c=c', $u->getParamsString());

		$u->addParam('b', 'b1');
		$this->assertEquals([
								'a' => 'z',
								'b' => ['b', 'b1'],
								'c' => 'c'
							],
							$u->getParams());
		$this->assertEquals('?a=z&b[]=b&b[]=b1&c=c', $u->getParamsString());

		$this->assertEquals('/Ark4ne?a=z&b[]=b&b[]=b1&c=c', (string)$u);
	}

	public function testFormat()
	{
		$urls = [
			'https://www.google.com/',
			'https://github.com/Ark4ne',
			'http://localhost/',
			'http://localhost/lara-test/home',
		];
		foreach ($urls as $url) {
			$u = new \Ark4ne\Helpers\URL($url);
			$this->assertEquals($url, (string)$u);
			$this->assertEquals($url, (string)$u->format($u->isSecure()));
		}
	}
}
