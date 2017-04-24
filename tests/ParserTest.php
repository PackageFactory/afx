<?php
use PHPUnit\Framework\TestCase;
use PackageFactory\Afx\Parser;

class ParserTest extends TestCase
{

    /**
     * @test
     */
    public function shouldParseSingleTag()
    {
        $parser = new Parser('<div></div>');
        $this->assertEquals([
            'identifier' => 'div',
            'props' => [],
            'children' => [],
            'selfClosing' => false
        ], $parser->parse());
    }

    /**
     * @test
     */
    public function shouldParseSingleSelfClosingTag()
    {
        $parser = new Parser('<div/>');
        $this->assertEquals([
            'identifier' => 'div',
            'props' => [],
            'children' => [],
            'selfClosing' => true
        ], $parser->parse());
    }

    /**
     * @test
     */
    public function shouldParseSingleSelfClosingTagWithWhitespaces()
    {
        $parser = new Parser('<div   />');
        $this->assertEquals([
            'identifier' => 'div',
            'props' => [],
            'children' => [],
            'selfClosing' => true
        ], $parser->parse());
    }

    /**
     * @test
     */
    public function shouldParseSingleTagWithWhitespaces()
    {
        $parser = new Parser('<div   ></div>');
        $this->assertEquals([
            'identifier' => 'div',
            'props' => [],
            'children' => [],
            'selfClosing' => false
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
            'children' => [],
            'selfClosing' => true
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
            'children' => [],
            'selfClosing' => true
        ], $parser->parse());
    }

    /**
     * @test
     */
    public function shouldParseSingleSelfClosingTagWithMultipleAttributesWrappedByMultipleWhitespaces()
    {
        $parser = new Parser('<div   prop="value"    anotherProp="Another Value"  />');
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
            'children' => [],
            'selfClosing' => true
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
            'children' => [],
            'selfClosing' => true
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
            'children' => [],
            'selfClosing' => false
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
            ],
            'selfClosing' => false
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
                        'children' => [],
                        'selfClosing' => true
                    ]
                ]
            ],
            'selfClosing' => false
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
                                    ],
                                    'selfClosing' => false
                                ]
                            ]
                        ],
                        'selfClosing' => false
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
                        ],
                        'selfClosing' => false
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
                                    ],
                                    'selfClosing' => false
                                ]
                            ]
                        ],
                        'selfClosing' => false
                    ]
                ]
            ],
            'selfClosing' => false
        ], $parser->parse());
    }

    /**
     * @test
     */
    public function shouldHandleWhitespace()
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
                        'children' => [],
                        'selfClosing' => true
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
                        ],
                        'selfClosing' => false
                    ]
                ],
                [
                    'type' => 'text',
                    'payload' => '
							     '
                ]
            ],
            'selfClosing' => false
        ], $parser->parse());
    }

    /**
     * @test
     * @expectedException \PackageFactory\Afx\Exception
     */
    public function shouldThrowExceptionForUnclosedTag()
    {
        $parser = new Parser('<div');
        $parser->parse();
    }

    /**
     * @test
     * @expectedException \PackageFactory\Afx\Exception
     */
    public function shouldThrowExceptionForUnclosedTagWithContent()
    {
        $parser = new Parser('<div>foo');
        $parser->parse();
    }

    /**
     * @test
     * @expectedException \PackageFactory\Afx\Exception
     */
    public function shouldThrowExceptionForUnclosedStringAttribute()
    {
        $parser = new Parser('<div foo="bar />');
        $parser->parse();
    }

    /**
     * @test
     * @expectedException \PackageFactory\Afx\Exception
     */
    public function shouldThrowExceptionForUnclosedAttributeExpression()
    {
        $parser = new Parser('<div foo={bar()/>');
        $parser->parse();
    }

    /**
     * @test
     * @expectedException \PackageFactory\Afx\Exception
     */
    public function shouldThrowExceptionForUnclosedContentExpression()
    {
        $parser = new Parser('<div>{bar()</div>');
        $parser->parse();
    }
}
