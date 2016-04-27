<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

use Symfony\Component\DomCrawler\Crawler;

class EmbedImageMessageFilter extends AbstractMessageFilter
{

    const REGEX_IMAGE_SOURCE = '#^//?(?<rel_path>.+)#';

    /**
     * @var string
     */
    protected $webRootDir;

    /**
     * @param string $webRootDir
     */
    public function __construct($webRootDir)
    {
        $this->webRootDir = $webRootDir;
    }

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
        $src = $imageElement->getAttribute('src');
        if(!preg_match(self::REGEX_IMAGE_SOURCE, $src, $match)) return;

        $relPath = $match['rel_path'];

        $path = $this->getLocalImagePath($relPath);
        if($path === null) return;

        $mime = mime_content_type($path);

        $image = new \Swift_Image(file_get_contents($path), $relPath, $mime);

        $message->setChildren(array_merge(
            $message->getChildren(),
            array($image)
        ));

        $imageElement->setAttribute('src', sprintf('cid:%s', $image->getId()));
    }

    /**
     * @param string $relPath
     * @return null|string
     */
    protected function getLocalImagePath($relPath)
    {
        $path = sprintf('%s/%s', $this->webRootDir, $relPath);
        if(!file_exists($path)) return null;
        return $path;
    }



}