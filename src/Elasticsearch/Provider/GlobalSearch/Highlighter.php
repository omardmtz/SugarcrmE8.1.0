<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch;

use Sugarcrm\Sugarcrm\Elasticsearch\Query\Highlighter\HighlighterInterface;

/**
 *
 * GlobalSearch Highlighter
 *
 */
class Highlighter implements HighlighterInterface
{
    /**
     * Global highlighter properties not explicitly
     * available on this object.
     * @var array
     */
    protected $globalProps = [];

    /**
     * @var array List of fields and its highlighter settings
     */
    protected $fields = [];

    /**
     * @var array Field arguments applied to every field
     */
    protected $defaultFieldArgs = [];

    /**
     * @var array List of pre tags
     */
    protected $preTags = ['<strong>'];

    /**
     * @var array List of post tags
     */
    protected $postTags = ['</strong>'];

    /**
     * @var integer Number of fragments
     */
    protected $numberOfFrags = 5;

    /**
     * @var integer Fragment size
     */
    protected $fragSize = 100;

    /**
     * @var boolean Require field match
     */
    protected $requireFieldMatch = true;

    /**
     * @var string Field encoder, accepts html or default
     */
    protected $encoder = 'html';

    /**
     * @var string Order highlights, defaults to score
     */
    protected $order = 'score';

    /**
     * Ctor
     */
    public function __construct()
    {
        // always require a field match by default
        $this->setRequiredFieldMatch(true);

        // default fragments
        $this->setNumberOfFrags(3);
        $this->setFragSize(255);

        // use _source and plain highlighter
        $this->setDefaultFieldArgs([
            'type' => 'plain',
            'force_source' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function build()
    {
        // generate global properties
        $properties = [
            'pre_tags' => $this->preTags,
            'post_tags' => $this->postTags,
            'require_field_match' => $this->requireFieldMatch,
            'number_of_fragments' => $this->numberOfFrags,
            'fragment_size' => $this->fragSize,
            'encoder' => $this->encoder,
            'order' => $this->order,
        ];
        $properties = array_merge($this->globalProps, $properties);

        // generate fields
        $fields = [];
        foreach ($this->fields as $field => $args) {
            $fields[$field] = array_merge($this->defaultFieldArgs, $args);
        }

        $properties['fields'] = $fields;
        return $properties;
    }

    /**
     * Set fields
     * @param array $fields
     * @return Highlighter
     */
    public function setFields(array $fields)
    {
        $this->fields = array_merge($this->fields, $fields);
        return $this;
    }

    /**
     * Set field arguments which are applied on every field
     * @param array $args
     * @return Highlighter
     */
    public function setDefaultFieldArgs(array $args)
    {
        $this->defaultFieldArgs = $args;
        return $this;
    }

    /**
     * Set list of pre tags
     * @param array $tags
     * @return Highlighter
     */
    public function setPreTags(array $tags)
    {
        $this->preTags = $tags;
        return $this;
    }

    /**
     * Get pre tags
     * @return array
     */
    public function getPreTags()
    {
        return $this->preTags;
    }

    /**
     * Set list of post tags
     * @param array $tags
     * @return \Sugarcrm\Sugarcrm\Elasticsearch\Component\Highlighter
     */
    public function setPostTags(array $tags)
    {
        $this->postTags = $tags;
        return $this;
    }

    /**
     * Get post tags
     * @return array
     */
    public function getPostTags()
    {
        return $this->postTags;
    }

    /**
     * Enable/disable required field match
     * @param boolean $toggle
     * @return Highlighter
     */
    public function setRequiredFieldMatch($toggle)
    {
        $this->requireFieldMatch = (bool) $toggle;
        return $this;
    }

    /**
     * Set global number of fragments
     * @param integer $value
     * @return Highlighter
     */
    public function setNumberOfFrags($value)
    {
        $this->numberOfFrags = (int) $value;
        return $this;
    }

    /**
     * Set global fragment size
     * @param integer $value
     * @return Highlighter
     */
    public function setFragSize($value)
    {
        $this->fragSize = (int) $value;
        return $this;
    }
}
