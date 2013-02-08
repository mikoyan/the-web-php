<?php

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Superdesk\View\Item as ItemView;

/**
 * @ODM\Document(
 *      collection="news_item"
 *  )
 */
class Item
{
    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @ODM\EmbedOne(targetDocument="ContentMeta")
     */
    private $contentMeta;

    /**
     * Render Item 
     */
    public function render()
    {
        $view = new ItemView();
        $view->id = $this->id;
        $view->headline = $this->contentMeta->render()->headline;
        $view->description = $this->contentMeta->render()->description;
        return $view;
    }
}
