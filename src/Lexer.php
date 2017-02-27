<?php
namespace PackageFactory\Afx;

/**
 * A primitive lexer that recognizes Afx-specific characters while iterating
 * through a string
 */
class Lexer
{
	/**
	 * The string to be iterated through
	 *
	 * @var string
	 */
	protected $string;

	/**
	 * The currently focused character
	 *
	 * @var string
	 */
	protected $currentCharacter;

	/**
	 * The current character position
	 *
	 * @var integer
	 */
	protected $characterPosition;

	/**
	 * Constructor
	 *
	 * @param string $string
	 */
	public function __construct($string)
	{
		$this->string = $string;
		$this->currentCharacter = $string{0};
		$this->characterPosition = 0;
	}

	/**
	 * Checks if the current character is whitespace
	 *
	 * @return boolean
	 */
	public function isWhiteSpace()
	{
		return ctype_space($this->currentCharacter);
	}

	/**
	 * Checks if the current character is a letter
	 *
	 * @return boolean
	 */
	public function isAlpha()
	{
		return ctype_alpha($this->currentCharacter);
	}

	/**
	 * Checks if the current character is alpha-numeric
	 *
	 * @return boolean
	 */
	public function isAlphaNumeric()
	{
		return ctype_alnum($this->currentCharacter);
	}

	/**
	 * Checks if the current character is a colon
	 *
	 * @return boolean
	 */
	public function isColon()
	{
		return $this->currentCharacter === ':';
	}

	/**
	 * Checks if the current character is a dot
	 *
	 * @return boolean
	 */
	public function isDot()
	{
		return $this->currentCharacter === '.';
	}

	/**
	 * Checks if the current character is a minus
	 *
	 * @return boolean
	 */
	public function isMinus()
	{
		return $this->currentCharacter === '-';
	}

	/**
	 * Checks if the current character is an underscore
	 *
	 * @return boolean
	 */
	public function isUnderscore()
	{
		return $this->currentCharacter === '_';
	}

	/**
	 * Checks if the current character is an equal sign
	 *
	 * @return boolean
	 */
	public function isEqualSign()
	{
		return $this->currentCharacter === '=';
	}

	/**
	 * Checks if the current character is an opening bracket
	 *
	 * @return boolean
	 */
	public function isOpeningBracket()
	{
		return $this->currentCharacter === '<';
	}

	/**
	 * Checks if the current character is a closing bracket
	 *
	 * @return boolean
	 */
	public function isClosingBracket()
	{
		return $this->currentCharacter === '>';
	}

	/**
	 * Checks if the current character is an opening curly brace
	 *
	 * @return boolean
	 */
	public function isOpeningBrace()
	{
		return $this->currentCharacter === '{';
	}

	/**
	 * Checks if the current character is a closing curly brace
	 *
	 * @return boolean
	 */
	public function isClosingBrace()
	{
		return $this->currentCharacter === '}';
	}

	/**
	 * Checks if the current character is a forward slash
	 *
	 * @return boolean
	 */
	public function isForwardSlash()
	{
		return $this->currentCharacter === '/';
	}

	/**
	 * Checks if the current character is a back slash
	 *
	 * @return boolean
	 */
	public function isBackSlash()
	{
		return $this->currentCharacter === '\\';
	}

	/**
	 * Checks if the current character is a single quote
	 *
	 * @return boolean
	 */
	public function isSingleQuote()
	{
		return $this->currentCharacter === '\'';
	}

	/**
	 * Checks if the current character is a double quote
	 *
	 * @return boolean
	 */
	public function isDoubleQuote()
	{
		return $this->currentCharacter === '"';
	}

	/**
	 * Checks if the iteration has ended
	 *
	 * @return boolean
	 */
	public function isEnd()
	{
		return $this->currentCharacter === null;
	}

	/**
	 * Rewinds the iteration by one step
	 *
	 * @return void
	 */
	public function rewind()
	{
		$this->currentCharacter = $this->string{--$this->characterPosition};
	}

	/**
	 * Returns the current character and moves one step forward
	 *
	 * @return string|null
	 */
	public function consume()
	{
		$c = $this->currentCharacter;
		if ($this->characterPosition < strlen($this->string) - 1) {
			$this->currentCharacter = $this->string{++$this->characterPosition};
		} else {
			$this->currentCharacter = null;
		}

		return $c;
	}
}
