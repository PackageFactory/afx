<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Prop
{
    public static function parse(Lexer $lexer)
    {
        while ($lexer->isWhitespace()) {
            $lexer->consume();
        }

        $identifier = Identifier::parse($lexer);

        if ($lexer->isEqualSign()) {
            $lexer->consume();
            switch (true) {
                case $lexer->isSingleQuote():
                case $lexer->isDoubleQuote():
                    $value = [
                        'type' => 'string',
                        'payload' => StringLiteral::parse($lexer)
                    ];
                    break;

                case $lexer->isOpeningBrace():
                    $expression = Expression::parse($lexer);
                    if (is_string($expression)) {
                        $value = [
                            'type' => 'expression',
                            'payload' => $expression
                        ];
                    } else {
                        $value = $expression;
                    }
                    break;
                default:
                    throw new Exception(sprintf(
                        'Prop-assignment "%s" was not followed by quotes or braces',
                        $identifier
                    ));
            }
        } elseif ($lexer->isWhiteSpace() || $lexer->isForwardSlash() || $lexer->isClosingBracket()) {
            $value = [
                'type' => 'boolean',
                'payload' => true
            ];
        } else {
            throw new Exception(sprintf('Prop identifier "%s" is neither assignment nor boolean', $identifier));
        }

        return [$identifier, $value];
    }
}
