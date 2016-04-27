<?php

namespace Kyoushu\InlineSwiftmailerTransport\MessageFilter;

abstract class AbstractMessageFilter implements MessageFilterInterface
{

    /**
     * @return AbstractMessageFilter
     */
    public static function create()
    {
        $ref = new \ReflectionClass(static::class);
        return $ref->newInstance();
    }

    /**
     * @return string
     */
    private function getId()
    {
        return spl_object_hash($this);
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param array $ids
     * @return $this
     */
    private function setMessageFilterIds(\Swift_Mime_Message $message, array $ids)
    {
        $message->getHeaders()->addTextHeader(self::HEADER_FILTER_LIST, implode(',', $ids));
        return $this;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @return array
     */
    private function getMessageFilterIds(\Swift_Mime_Message $message)
    {
        if(!$message->getHeaders()->has(self::HEADER_FILTER_LIST)){
            return array();
        }
        $ids = $message->getHeaders()->get(self::HEADER_FILTER_LIST);
        return explode(',', $ids);
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param string $id
     * @return $this
     */
    private function addMessageFilterId(\Swift_Mime_Message $message, $id)
    {
        $ids = $this->getMessageFilterIds($message);
        $key = array_search($ids, $ids);
        if($key === false) return $this;
        $ids[] = $id;
        $this->setMessageFilterIds($message, $ids);
        return $this;
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param string $id
     */
    private function removeMessageFilterId(\Swift_Mime_Message $message, $id)
    {
        $ids = $this->getMessageFilterIds($message);
        $key = array_search($ids, $ids);
        if($key === false) return;
        unset($ids[$key]);
        $this->setMessageFilterIds($message, $ids);
    }

    /**
     * @param \Swift_Mime_Message $message
     * @return bool
     */
    public function isMessageFiltered(\Swift_Mime_Message $message)
    {
        return in_array($this->getId(), $this->getMessageFilterIds($message));
    }

    /**
     * @param \Swift_Mime_Message $message
     * @param bool $filtered
     * @return $this
     */
    public function setMessageFiltered(\Swift_Mime_Message $message, $filtered)
    {
        if($filtered){
            $this->addMessageFilterId($message, $this->getId());
        }
        else{
            $this->removeMessageFilterId($message, $this->getId());
        }
        return $this;
    }

}