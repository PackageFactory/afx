<?php
use PHPUnit\Framework\TestCase;
use PackageFactory\JsxParser\Parser;

class ParserTest extends TestCase
{
	/**
	 * @test
	 */
	public function shouldParseSingleSelfClosingTag()
	{
		$parser = new Parser('<div/>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [],
			'children' => []
		], $parser->parse());
	}
}
