<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Lexer;

class StringLiteral
{
    public static function parse(Lexer $lexer)
    {
        $openingQuoteSign = '';
        $contents = '';
        $willBeEscaped = false;
        if ($lexer->isSingleQuote() || $lexer->isDoubleQuote()) {
            $openingQuoteSign = $lexer->consume();
        }

        while (!$lexer->isEnd()) {
            if ($lexer->isBackSlash() && !$willBeEscaped) {
                $willBeEscaped = true;
                $lexer->consume();
                continue;
            }

            if ($lexer->isSingleQuote() || $lexer->isDoubleQuote()) {
                $closingQuoteSign = $lexer->consume();
                if (!$willBeEscaped && $openingQuoteSign === $closingQuoteSign) {
                    return $contents;
                }

                $contents .= $closingQuoteSign;
                $willBeEscaped = false;
                continue;
            }

            $contents .= $lexer->consume();
            $willBeEscaped = false;
        }
    }
}
