<?php

namespace Kyoushu\InlineSwiftmailerTransport\Tests\MessageFilter;

use Kyoushu\InlineSwiftmailerTransport\MessageFilter\EmbedImageMessageFilter;
use Symfony\Component\DomCrawler\Crawler;

class EmbedImageMessageFilterTest extends MessageFilterTestCase
{

    public function testFilterMessage()
    {
        $message = new \Swift_Message('Foo', $this->loadHtml('images'), 'text/html');

        $webRootDir = __DIR__ . '/../Resources/web';

        $transport = $this->createInlineTransport();
        $transport->addMessageFilter(new EmbedImageMessageFilter($webRootDir));
        $transport->send($message);

        $crawler = new Crawler($message->getBody());
        /** @var \DOMElement[] $images */
        $images = $crawler->filter('img');

        $this->assertCount(4, $images);

        foreach($images as $image){
            $this->assertRegExp('/cid:.+/', $image->getAttribute('src'));
        }

        $transport->send($message);

    }

}