<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

use Symfony\Component\DomCrawler\Crawler;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class InlineCssMessageFilter extends AbstractWebAssetMessageFilter
{

    /**
     * @param \Swift_Mime_Message $message
     * @throws \TijsVerkoyen\CssToInlineStyles\Exception
     */
    public function filterMessage(\Swift_Mime_Message $message)
    {
        if($this->isMessageFiltered($message)) return;

        $body = $message->getBody();

        $inliner = new CssToInlineStyles();
        $css = $this->extractCss($body);
        $inliner->setCSS($css);
        $inliner->setHTML($body);
        $body = $inliner->convert();

        $message->setBody($body);

        $this->setMessageFiltered($message, true);
    }

    /**
     * @param string $body
     * @return string
     */
    public function extractCss($body)
    {
        $crawler = new Crawler($body);

        /** @var \DOMElement[] $elements */
        $elements = $crawler->filter('link[rel=stylesheet]');

        $css = '';

        foreach($elements as $element){
            $url = $element->getAttribute('href');
            if(!$this->assetExists($url)) continue;
            $path = $this->getAssetPath($url);
            $css .= file_get_contents($path);
        }

        return $css;
    }

}