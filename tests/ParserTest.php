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

	/**
	 * @test
	 */
	public function shouldParseSingleSelfClosingTagWithSingleAttribute()
	{
		$parser = new Parser('<div prop="value"/>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [
				'prop' => [
					'type' => 'string',
					'payload' => 'value'
				]
			],
			'children' => []
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldParseSingleSelfClosingTagWithMultipleAttributes()
	{
		$parser = new Parser('<div prop="value" anotherProp="Another Value"/>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [
				'prop' => [
					'type' => 'string',
					'payload' => 'value'
				],
				'anotherProp' => [
					'type' => 'string',
					'payload' => 'Another Value'
				]
			],
			'children' => []
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function propsCanHaveDashesInTheirName()
	{
		$parser = new Parser('<div prop-1="value" prop-2="Another Value"/>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [
				'prop-1' => [
					'type' => 'string',
					'payload' => 'value'
				],
				'prop-2' => [
					'type' => 'string',
					'payload' => 'Another Value'
				]
			],
			'children' => []
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldParseSingleTagWithSeparateClosingTag()
	{
		$parser = new Parser('<div></div>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [],
			'children' => []
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldParseSingleTagWithSeparateClosingTagAndOneChild()
	{
		$parser = new Parser('<div>Hello World!</div>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [],
			'children' => [
				[
					'type' => 'text',
					'payload' => 'Hello World!'
				]
			]
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldParseNestedSelfClosingTag()
	{
		$parser = new Parser('<div><input/></div>');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [],
			'children' => [
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'input',
						'props' => [],
						'children' => []
					]
				]
			]
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldParseNestedTags()
	{
		$parser = new Parser('<article><header><div>Header</div></header><div>Content</div><footer><div>Footer</div></footer></article>');
		$this->assertEquals([
			'identifier' => 'article',
			'props' => [],
			'children' => [
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'header',
						'props' => [],
						'children' => [
							[
								'type' => 'node',
								'payload' => [
									'identifier' => 'div',
									'props' => [],
									'children' => [
										[
											'type' => 'text',
											'payload' => 'Header'
										]
									]
								]
							]
						]
					]
				],
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'div',
						'props' => [],
						'children' => [
							[
								'type' => 'text',
								'payload' => 'Content'
							]
						]
					]
				],
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'footer',
						'props' => [],
						'children' => [
							[
								'type' => 'node',
								'payload' => [
									'identifier' => 'div',
									'props' => [],
									'children' => [
										[
											'type' => 'text',
											'payload' => 'Footer'
										]
									]
								]
							]
						]
					]
				]
			]
		], $parser->parse());
	}

	/**
	 * @test
	 */
	public function shouldHandletWhitespace()
	{
		$parser = new Parser('   <div>
							<input/>
					<label>Some

					Text</label>
							     </div>   ');
		$this->assertEquals([
			'identifier' => 'div',
			'props' => [],
			'children' => [
				[
					'type' => 'text',
					'payload' => '
							'
				],
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'input',
						'props' => [],
						'children' => []
					]
				],
				[
					'type' => 'text',
					'payload' => '
					'
				],
				[
					'type' => 'node',
					'payload' => [
						'identifier' => 'label',
						'props' => [],
						'children' => [
							[
								'type' => 'text',
								'payload' => 'Some

					Text'
							]
						]
					]
				],
				[
					'type' => 'text',
					'payload' => '
							     '
				]
			]
		], $parser->parse());
	}
}
