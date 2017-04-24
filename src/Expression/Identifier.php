<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Lexer;

class Identifier
{
    public static function parse(Lexer $lexer)
    {
        $identifier = '';

        while ($lexer->isAlphaNumeric() ||
            $lexer->isDot() ||
            $lexer->isColon() ||
            $lexer->isMinus() ||
            $lexer->isUnderscore() ||
            $lexer->isAt()
        ) {
            $identifier .= $lexer->consume();
        }

        return $identifier;
    }
}
