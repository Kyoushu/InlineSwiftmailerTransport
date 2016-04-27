<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

use Symfony\Component\DomCrawler\Crawler;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class InlineCssMessageFilter extends AbstractMessageFilter
{

    /**
     * @param \Swift_Mime_Message $message
     * @return bool
     */
    public function supportsMessage(\Swift_Mime_Message $message)
    {
        return $message->getContentType() === 'text/plain';
    }


    public function filterMessage(\Swift_Mime_Message $message)
    {
        if(!$this->supportsMessage($message)) return;
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
    protected function extractCss($body)
    {
        $css = '';
        $crawler = new Crawler($body);

        foreach($crawler->filter('style') as $node){
            /** @var \DOMElement $node */
            $css .= $node->nodeValue;
        }

        $css = $this->tidyCss($css);

        return $css;
    }

    /**
     * @param string $css
     * @return string
     */
    protected function tidyCss($css)
    {
        $tidy = new \csstidy();
        $tidy->parse($css);
        return $tidy->print->plain();
    }

}