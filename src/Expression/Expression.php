<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Expression
{
    public static function parse(Lexer $lexer)
    {
        $contents = '';
        $braceCount = 0;
        if ($lexer->isOpeningBrace()) {
            $lexer->consume();
        }

        while (true) {
            if ($lexer->isEnd()) {
                throw new Exception('Unclosed Expression');
            }

            if ($lexer->isOpeningBrace()) {
                $braceCount++;
            }

            if ($lexer->isClosingBrace()) {
                if ($braceCount === 0) {
                    $lexer->consume();
                    return $contents;
                }

                $braceCount--;
            }

            $contents .= $lexer->consume();
        }
    }
}
