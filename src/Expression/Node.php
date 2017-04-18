<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Node
{
    public static function parse(Lexer $lexer)
    {
        while ($lexer->isWhitespace()) {
            $lexer->consume();
        }

        if ($lexer->isOpeningBracket()) {
            $lexer->consume();
        }

        $identifier = Identifier::parse($lexer);
        $props = [];
        $children = [];

        if ($lexer->isWhitespace()) {
            while ($lexer->isWhitespace()) {
                $lexer->consume();
            }
            while (!$lexer->isForwardSlash() && !$lexer->isClosingBracket()) {
                list($propIdentifier, $value) = Prop::parse($lexer);
                $props[$propIdentifier] = $value;
                while ($lexer->isWhitespace()) {
                    $lexer->consume();
                }
            }
        }

        if ($lexer->isForwardSlash()) {
            $lexer->consume();

            if ($lexer->isClosingBracket()) {
                $lexer->consume();

                return [
                    'identifier' => $identifier,
                    'props' => $props,
                    'children' => $children,
                    'selfClosing' => true
                ];
            }
        }

        if ($lexer->isClosingBracket()) {
            $lexer->consume();
        }

        $children = Children::parse($lexer);

        while ($lexer->isWhitespace()) {
            $lexer->consume();
        }

        if ($lexer->isOpeningBracket()) {
            $lexer->consume();

            if ($lexer->isForwardSlash()) {
                $lexer->consume();
            }
        }

        $closingIdentifier = Identifier::parse($lexer);

        if ($lexer->isClosingBracket()) {
            $lexer->consume();

            if ($closingIdentifier === $identifier) {
                return [
                    'identifier' => $identifier,
                    'props' => $props,
                    'children' => $children,
                    'selfClosing' => false
                ];
            }
        }

        if ($lexer->isEnd()) {
            throw new Exception(sprintf('Tag %s was is not closed', $identifier));
        }
    }
}
