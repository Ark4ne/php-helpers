<?php

class HtmlTest extends PHPUnit_Framework_TestCase
{

	private $asserts;

	public function __construct()
	{
		$this->asserts = [
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
	}

	public function testObjectFormat()
	{
		foreach ($this->asserts as $assert) {
			$this->assertEquals($assert->expected, \Ark4ne\Helpers\Html::jsonValue($assert->test));
			$this->assertEquals($assert->expected, json_html_string($assert->test));
		}
	}

	public function testJsonFormat()
	{
		foreach ($this->asserts as $assert) {
			$this->assertEquals("attr='" . $assert->expected . "'",
								\Ark4ne\Helpers\Html::jsonAttr('attr', $assert->test));
			$this->assertEquals("attr='" . $assert->expected . "'",
								json_html_attr('attr', $assert->test));
		}
	}
}
