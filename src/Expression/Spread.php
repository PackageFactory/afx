<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Spread
{
    public static function parse(Lexer $lexer)
    {
        $contents = '';
        $braceCount = 0;

        if ($lexer->isOpeningBrace() && $lexer->peek(4) === '{...') {
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
        } else {
            throw new Exception('Spread without braces');
        }

        while (true) {
            if ($lexer->isEnd()) {
                throw new Exception(sprintf('Unfinished Spread "%s"', $contents));
            }

            if ($lexer->isOpeningBrace()) {
                $braceCount++;
            }

            if ($lexer->isClosingBrace()) {
                if ($braceCount === 0) {
                    $lexer->consume();
                    return [
                        'type' => 'expression',
                        'payload' => $contents
                    ];
                }

                $braceCount--;
            }

            $contents .= $lexer->consume();
        }
    }
}
