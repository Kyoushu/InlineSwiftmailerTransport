<?php

namespace Kyoushu\InlineSwiftmailerTransport\CssInliner;

use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class TijsverkoyenCssInliner extends AbstractCssInliner
{

    public function inlineCss($html)
    {
        $inliner = new CssToInlineStyles();
        $css = $this->extractCss($html);
        $inliner->setCSS($css);
        $inliner->setHTML($html);
        return $inliner->convert();
    }

}