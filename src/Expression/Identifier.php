<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Lexer;

class Identifier
{
	public static function parse(Lexer $lexer)
	{
		$identifier = '';
		if ($lexer->isAlpha()) {
			$identifier .= $lexer->consume();
		}

		while(
			$lexer->isAlphaNumeric() ||
			$lexer->isDot() ||
			$lexer->isColon() ||
			$lexer->isMinus() ||
			$lexer->isUnderscore()
		) {
			$identifier .= $lexer->consume();
		}

		return $identifier;
	}
}
