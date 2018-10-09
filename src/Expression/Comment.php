<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Comment
{
    public static function parse(Lexer $lexer)
    {
        if ($lexer->isOpeningBracket() && $lexer->peek(4) === '<!--') {
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
            $lexer->consume();
        } else {
            throw new Exception(sprintf('Unexpected comment start'));
        }

        $currentComment = '';

        while (true) {
            if ($lexer->isMinus() && $lexer->peek(3) === '-->') {
                $lexer->consume();
                $lexer->consume();
                $lexer->consume();
                return $currentComment;
            }

            if ($lexer->isEnd()) {
                throw new Exception(sprintf('Comment not closed.'));
            }

            $currentComment .= $lexer->consume();
        }
    }
}
