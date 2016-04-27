<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

abstract class AbstractWebAssetMessageFilter extends AbstractMessageFilter
{

    const REGEX_ABSOLUTE_URL = '#^//?(?<rel_path>.+)#';

    /**
     * @var string
     */
    private $webRootDir;

    /**
     * @param string $webRootDir
     */
    public function __construct($webRootDir)
    {
        $this->webRootDir = $webRootDir;
    }

    /**
     * @return string
     */
    public function getWebRootDir()
    {
        return $this->webRootDir;
    }

    /**
     * @param string $url
     * @return bool
     */
    protected function assetExists($url)
    {
        $path = $this->getAssetPath($url);
        if ($path === null) return false;
        return file_exists($path);
    }

    /**
     * @param string $url
     * @return null|string
     */
    protected function getAssetPath($url)
    {
        $relPath = $this->getRelAssetPath($url);
        if($relPath === null) return null;
        $path = sprintf('%s/%s', $this->getWebRootDir(), $relPath);
        return $path;
    }

    /**
     * @param string $url
     * @return null|string
     */
    protected function getRelAssetPath($url)
    {
        if(!preg_match(self::REGEX_ABSOLUTE_URL, $url, $match)) return null;
        return $match['rel_path'];
    }

}