<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests\CssInliner;

use Kyoushu\InlineSwiftmailerTransport\CssInliner\AbstractCssInliner;
use Kyoushu\InlineSwiftmailerTransport\Tests\TestCase;

class AbstractCssInlinerTest extends TestCase
{

    public function testExtractCss()
    {
        $cssInliner = new MockCssInliner();
        $html = $this->getHtml('original');
        $css = $cssInliner->extractCss($html);
        $this->assertEquals("p {\nfont-family:sans-serif\n}\n\ndiv {\npadding:10px\n}", $css);
    }

}

class MockCssInliner extends AbstractCssInliner
{

    public function inlineCss($html)
    {
        return $html;
    }

}