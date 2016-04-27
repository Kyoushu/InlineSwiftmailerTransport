<?php

namespace Kyoushu\InlineSwiftmailerTransport\CssInliner;

use Symfony\Component\DomCrawler\Crawler;

abstract class AbstractCssInliner implements CssInlinerInterface
{

    /**
     * @param string $html
     * @return string
     */
    public function extractCss($html)
    {
        $css = '';
        $crawler = new Crawler($html);

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