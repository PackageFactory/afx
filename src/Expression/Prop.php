<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Lexer;

class Prop
{
    public static function parse(Lexer $lexer)
    {
        while ($lexer->isWhitespace()) {
            $lexer->consume();
        }

        $identifier = Identifier::parse($lexer);
        $value = [
            'type' => 'boolean',
            'payload' => false
        ];

        if ($lexer->isEqualSign()) {
            $lexer->consume();
        }

        switch (true) {
            case $lexer->isSingleQuote():
            case $lexer->isDoubleQuote():
                $value = [
                'type' => 'string',
                'payload' => StringLiteral::parse($lexer)
                ];
                break;

            case $lexer->isOpeningBrace():
                $value = [
                'type' => 'expression',
                'payload' => Expression::parse($lexer)
                ];
                break;

            case $lexer->isWhiteSpace():
            case $lexer->isForwardSlash():
            case $lexer->isClosingBracket():
                $value = [
                'type' => 'boolean',
                'payload' => true
                ];
                break;
        }

        return [$identifier, $value];
    }
}
