<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

use Symfony\Component\DomCrawler\Crawler;

class EmbedImageMessageFilter extends AbstractWebAssetMessageFilter
{

    /**
     * @param \Swift_Mime_Message $message
     */
    public function filterMessage(\Swift_Mime_Message $message)
    {

        if($this->isMessageFiltered($message)) return;

        $body = $message->getBody();

        $crawler = new Crawler($body);

        /** @var \DOMElement[] $elements */
        $elements = $crawler->filter('img');

        foreach($elements as $element){
            $this->embedImage($element, $message);
        }

        $body = $crawler->html();
        $message->setBody($body);

        $this->setMessageFiltered($message, true);
    }

    /**
     * @param \DOMElement $imageElement
     * @param \Swift_Mime_Message $message
     */
    protected function embedImage(\DOMElement $imageElement, \Swift_Mime_Message $message)
    {
        $url = $imageElement->getAttribute('src');
        if(!$this->assetExists($url)) return;

        $relPath = $this->getRelAssetPath($url);
        $path = $this->getAssetPath($url);

        $mime = mime_content_type($path);
        $image = new \Swift_Image(file_get_contents($path), $relPath, $mime);

        $message->setChildren(array_merge(
            $message->getChildren(),
            array($image)
        ));

        $imageElement->setAttribute('src', sprintf('cid:%s', $image->getId()));
    }

}