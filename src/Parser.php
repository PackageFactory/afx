<?php
namespace PackageFactory\Afx;

class Parser
{
	public function __construct($string)
	{
		$this->lexer = new Lexer($string);
	}

	public function parse()
	{
		return Expression\Node::parse($this->lexer);
	}
}
