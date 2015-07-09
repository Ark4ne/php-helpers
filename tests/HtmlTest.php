<?php

class HtmlTest extends PHPUnit_Framework_TestCase
{

	private $jsonAsserts;

	private $arrayAsserts;

	public function __construct()
	{
		$this->jsonAsserts = [
			object([
					   'test'     => 10,
					   'expected' => '10'
				   ]),
			object([
					   'test'     => "10",
					   'expected' => '"10"'
				   ]),
			object([
					   'test'     => "1'0",
					   'expected' => '"1\u20190"'
				   ]),
			object([
					   'test'     => [10],
					   'expected' => "[10]"
				   ]),
			object([
					   'test'     => [10, "10", "1'0"],
					   'expected' => '[10,"10","1\u20190"]'
				   ]),
			object([
					   'test'     => object([10, "10", "1'0"]),
					   'expected' => '{"0":10,"1":"10","2":"1\u20190"}'
				   ])
		];

		$this->arrayAsserts = [
			object([
					   'test'     => [10],
					   'expected' => "10"
				   ]),
			object([
					   'test'     => [10, "10", "1'0"],
					   'expected' => '10,10,1\u20190'
				   ]),
			object([
					   'test'     => [10, "10", "test"],
					   'expected' => '10,10,test'
				   ])];
	}

	public function testObjectFormat()
	{
		foreach ($this->jsonAsserts as $assert) {
			$this->assertEquals($assert->expected, \Ark4ne\Helpers\Html::jsonValue($assert->test));
			$this->assertEquals($assert->expected, json_html_string($assert->test));
		}
	}

	public function testJsonFormat()
	{
		foreach ($this->jsonAsserts as $assert) {
			$this->assertEquals("attr='" . $assert->expected . "'",
								\Ark4ne\Helpers\Html::jsonAttr('attr', $assert->test));
			$this->assertEquals("attr='" . $assert->expected . "'",
								json_html_attr('attr', $assert->test));
		}
	}

	public function testArrayStringFormat()
	{
		foreach ($this->arrayAsserts as $assert) {
			$this->assertEquals($assert->expected, \Ark4ne\Helpers\Html::arrayValue($assert->test));
			$this->assertEquals($assert->expected, array_html_string($assert->test));
		}
	}

	public function testArrayFormat()
	{
		foreach ($this->arrayAsserts as $assert) {
			$this->assertEquals("attr='" . $assert->expected . "'",
								\Ark4ne\Helpers\Html::arrayAttr('attr', $assert->test));
			$this->assertEquals("attr='" . $assert->expected . "'",
								array_html_attr('attr', $assert->test));
		}
	}
}
