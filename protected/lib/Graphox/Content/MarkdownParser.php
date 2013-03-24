<?php

/**
 * Wrapper arround markdownparser
 * @package Graphox\Content
 * @author killme
 */

namespace Graphox\Content;

/**
 * Markdown content encoder
 * @todo pass \CHtmlpurifier instance / config array in constructor
 */
class MarkdownParser implements IContentEncoder
{

    private $htmlPurifier;

    public function __construct(HtmlPurifier $purifier)
    {
        $this->htmlPurifier = $purifier;
    }

    protected function getPurifier()
    {
        return $this->htmlPurifier;
    }

    /**
     * Returns an instance to a cached markdown parser.
     * @staticvar \CMarkdownParser $parser
     * @return \CMarkdownParser
     */
    protected function getParser()
    {
        static $parser;

        if (!isset($parser))
        {
            $parser = new \CMarkdownParser;
        }

        return $parser;
    }

    /**
     * {@inheritdoc}
     */
    public function encodeContent(IHaveContent $content)
    {
        $content->setContent(
                $this->encodeString(
                        $content->getSource()
                )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function encodeString($string)
    {
        return $this->getPurifier()->encodeString($this->getParser()->transform($string));
    }

}

