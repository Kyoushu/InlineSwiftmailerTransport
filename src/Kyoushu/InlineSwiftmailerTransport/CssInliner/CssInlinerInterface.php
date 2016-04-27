<?php

namespace Kyoushu\InlineSwiftmailerTransport\CssInliner;

interface CssInlinerInterface
{

    /**
     * @param string $html
     * @return string
     */
    public function extractCss($html);

    /**
     * @param string $html
     * @return string
     */
    public function inlineCss($html);

}