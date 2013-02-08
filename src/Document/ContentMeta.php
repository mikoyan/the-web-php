<?php

namespace Superdesk\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\EmbeddedDocument
 */
class ContentMeta
{
    /**
     * @ODM\Field
     */
    private $headline;

    /**
     * @ODM\Field
     */
    private $description;

    /**
     * Render Item 
     */
    public function render()
    {
        return (object) array(
            'headline' => $this->headline,
            'description' => $this->description,
        );
    }
}
