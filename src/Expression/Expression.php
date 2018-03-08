<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;
use PackageFactory\Afx\Parser;

class Expression
{
    public static function parse(Lexer $lexer)
    {
        $contents = '';
        $braceCount = 0;

        if ($lexer->isOpeningBrace()) {
            $lexer->consume();
        } else {
            throw new Exception('Expression without braces');
        }

        while (true) {
            if ($lexer->isEnd()) {
                throw new Exception(sprintf('Unfinished Expression "%s"', $contents));
            }

            if ($lexer->isOpeningBrace()) {
                $braceCount++;
            }

            if ($lexer->isClosingBrace()) {
                if ($braceCount === 0) {
                    $lexer->consume();
                    return self::postProcessContents($contents);
                }

                $braceCount--;
            }

            $contents .= $lexer->consume();
        }
    }

    protected static function postProcessContents($contents)
    {
        $trimmedContents = trim($contents);

        if ($trimmedContents{0} === '<') {
            $parser = new Parser($trimmedContents);
            return $parser->parse()[0];
        }

        return $contents;
    }
}
