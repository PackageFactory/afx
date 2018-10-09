<?php
namespace PackageFactory\Afx\Expression;

use PackageFactory\Afx\Exception;
use PackageFactory\Afx\Lexer;

class NodeList
{
    public static function parse(Lexer $lexer)
    {
        $contents = [];
        $currentText = '';
        while (!$lexer->isEnd()) {
            if ($lexer->isOpeningBracket()) {
                $lexer->consume();
                if ($currentText) {
                    $contents[] = [
                        'type' => 'text',
                        'payload' => $currentText
                    ];
                }
                if ($lexer->isForwardSlash()) {
                    $lexer->rewind();
                    return $contents;
                } elseif ($lexer->isExclamationMark()) {
                    $lexer->rewind();
                    $contents[] = [
                        'type' => 'comment',
                        'payload' => Comment::parse($lexer)
                    ];
                    $currentText = '';
                    continue;
                } else {
                    $lexer->rewind();
                    $contents[] = [
                        'type' => 'node',
                        'payload' => Node::parse($lexer)
                    ];
                    $currentText = '';
                    continue;
                }
            }

            if ($lexer->isOpeningBrace()) {
                if ($currentText) {
                    $contents[] = [
                        'type' => 'text',
                        'payload' => $currentText
                    ];
                }

                $contents[] = [
                    'type' => 'expression',
                    'payload' => Expression::parse($lexer)
                ];
                $currentText = '';
                continue;
            }

            $currentText .= $lexer->consume();
        }

        if ($lexer->isEnd() && $currentText) {
            $contents[] = [
                'type' => 'text',
                'payload' => $currentText
            ];
        }

        return $contents;
    }
}
