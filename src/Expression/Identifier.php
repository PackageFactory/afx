<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Identifier
{
    public static function parse(Lexer $lexer)
    {
        $identifier = '';

        while (true) {
            switch (true) {
                case $lexer->isAlphaNumeric():
                case $lexer->isDot():
                case $lexer->isColon():
                case $lexer->isMinus():
                case $lexer->isUnderscore():
                case $lexer->isAt():
                    $identifier .= $lexer->consume();
                    break;
                case $lexer->isEqualSign():
                case $lexer->isWhiteSpace():
                case $lexer->isClosingBracket():
                case $lexer->isForwardSlash():
                    return $identifier;
                    break;
                default:
                    $unexpected_character = $lexer->consume();
                    throw new Exception(sprintf(
                        'Unexpected character "%s" in identifier "%s"',
                        $unexpected_character,
                        $identifier
                    ));
            }
        }
    }
}
