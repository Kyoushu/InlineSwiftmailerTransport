<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests\MessageFilter;

use Kyoushu\InlineSwiftmailerTransport\MessageFilter\InlineCssMessageFilter;

class InlineCssMessageFilterTest extends MessageFilterTestCase
{

    public function testFilterMessage()
    {
        $message = new \Swift_Message('Foo', $this->loadHtml('included_css'), 'text/html');

        $webRootDir = __DIR__ . '/../Resources/web';

        $transport = $this->createInlineTransport();
        $transport->addMessageFilter(new InlineCssMessageFilter($webRootDir));
        $transport->send($message);

        $this->assertBodyElementAttributesEquals($message, 'div', 'style', 'padding: 10px;');
        $this->assertBodyElementAttributesEquals($message, 'p', 'style', 'font-family: sans-serif;');
    }

}