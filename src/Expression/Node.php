<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class Node
{
    public static function parse(Lexer $lexer)
    {
        if ($lexer->isOpeningBracket()) {
            $lexer->consume();
        }

        $identifier = Identifier::parse($lexer);

        try {
            $attributes = [];
            $children = [];

            if ($lexer->isWhitespace()) {
                while ($lexer->isWhitespace()) {
                    $lexer->consume();
                }
                while (!$lexer->isForwardSlash() && !$lexer->isClosingBracket()) {
                    if ($lexer->isOpeningBrace()) {
                        $attributes[] = [
                            'type' => 'spread',
                            'payload' => Spread::parse($lexer)
                        ];
                    } else {
                        $attributes[] = [
                            'type' => 'prop',
                            'payload' => Prop::parse($lexer)
                        ];
                    }
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
                        'attributes' => $attributes,
                        'children' => $children,
                        'selfClosing' => true
                    ];
                } else {
                    throw new Exception(sprintf('Self closing tag "%s" misses closing bracket.', $identifier));
                }
            }

            if ($lexer->isClosingBracket()) {
                $lexer->consume();
            } else {
                throw new Exception(sprintf('Tag "%s" did not end with closing bracket.', $identifier));
            }

            $children = NodeList::parse($lexer);

            if ($lexer->isOpeningBracket()) {
                $lexer->consume();

                if ($lexer->isForwardSlash()) {
                    $lexer->consume();
                } else {
                    throw new Exception(sprintf(
                        'Opening-bracket for closing of tag "%s" was not followed by slash.',
                        $identifier
                    ));
                }
            } else {
                throw new Exception(sprintf(
                    'Opening-bracket for closing of tag "%s" expected.',
                    $identifier
                ));
            }

            $closingIdentifier = Identifier::parse($lexer);

            if ($closingIdentifier !== $identifier) {
                throw new Exception(sprintf(
                    'Closing-tag identifier "%s" did not match opening-tag identifier "%s".',
                    $closingIdentifier,
                    $identifier
                ));
            }

            if ($lexer->isClosingBracket()) {
                $lexer->consume();
                return [
                    'identifier' => $identifier,
                    'attributes' => $attributes,
                    'children' => $children,
                    'selfClosing' => false
                ];
            } else {
                throw new Exception(sprintf('Closing tag "%s" did not end with closing-bracket.', $identifier));
            }

            if ($lexer->isEnd()) {
                throw new Exception(sprintf('Tag was %s is not closed.', $identifier));
            }
        } catch (Exception $e) {
            throw new Exception(sprintf('<%s> %s', $identifier, $e->getMessage()));
        }
    }
}
