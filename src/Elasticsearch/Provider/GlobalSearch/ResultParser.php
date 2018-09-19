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

use Sugarcrm\Sugarcrm\Elasticsearch\Query\Result\ParserInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;

/**
 *
 * Result parser for GlobalSearch Provider
 *
 */
class ResultParser implements ParserInterface
{
    /**
     * List of fields to be remapped for _source
     * @var array
     */
    private $sourceRemap = [];

    /**
     * List of fields to be remapped for _highlight
     * @var array
     */
    private $highlightRemap = [];

    /**
     * @var Highlighter
     */
    private $highlighter;

    /**
     * Ctor
     * @param Highlighter $highlighter
     */
    public function __construct(Highlighter $highlighter)
    {
        $this->highlighter = $highlighter;
    }

    /**
     * {@inheritdoc}
     *
     * Normalize _source fields and apply remapping as defined by the provider.
     */
    public function parseSource(\Elastica\Result $result)
    {
        $parsed = [];
        $src = $result->getSource();
        foreach ($result->getSource() as $field => $value) {
            $field = $this->normalizeFieldName($field, $this->sourceRemap);
            $parsed[$field] = $value;
        }
        return $parsed;
    }

    /**
     * Add _source remapping
     * @param string[] $remap
     */
    public function addSourceRemap(array $remap)
    {
        $this->sourceRemap = array_merge($this->sourceRemap, $remap);
    }

    /**
     * {@inheritdoc}
     *
     * Apply _highlight field remapping as defined by the provider.
     *
     * Additionally the following parsing/formatting is performed:
     * - Ensure pre/post tags are present adding them ourselves if missing
     * - Handle subfield highlights as an array
     * - Filter out duplicate highlights
     */
    public function parseHighlights(\Elastica\Result $result)
    {
        $parsed = [];
        foreach ($result->getHighlights() as $field => $values) {
            // Normalize the field name and remap
            $normField = $this->normalizeFieldName($field, $this->highlightRemap);

            foreach ($values as $value) {
                // Explicitly add highlighter tags if non present
                if (strpos($value, $this->getPreTag()) === false) {
                    $value = $this->getPreTag() . $value . $this->getPostTag();
                }

                // For subfields maintain k->v pair
                if ($subField = $this->getSubFieldName($field)) {
                    $value = [$subField => [$value]];
                } else {
                    $value = [$value];
                }

                // Append value and filter duplicates
                if (isset($parsed[$normField])) {
                    $parsed[$normField] = $parsed[$normField] + $value;
                } else {
                    $parsed[$normField] = $value;
                }
            }
        }
        return $parsed;
    }

    /**
     * Add _highlight remapping
     * @param string[] $remap
     */
    public function addHighlightRemap(array $remap)
    {
        $this->highlightRemap = array_merge($this->highlightRemap, $remap);
    }

    /**
     * Normalize field name to the primary name, remove any prefix
     * and applying field remapping if defined.
     *
     * @param string $field
     * @param array $remap
     * @return string
     */
    protected function normalizeFieldName($field, array $remap)
    {
        if (preg_match('/^.*' . Mapping::PREFIX_SEP . '([^.]*).*$/', $field, $matches)) {
            $field = $matches[1];
        }
        return isset($remap[$field]) ? $remap[$field] : $field;
    }

    /**
     * Return the sub field value for given field is any is present. Otherwise
     * an empty string will be returned.
     *
     * Example field name with sub-field:
     *  input: Accounts__email_search.secondary.gs_email_wildcard
     *  output: secondary
     *
     *  Example field name without sub-field:
     *   input: Contacts__first_name.gs_string_wildcard
     *   output: empty string
     *
     * @param string $field
     * @return string
     */
    protected function getSubFieldName($field)
    {
        $subField = '';
        $sep = '\.';
        if (preg_match('/^.*' . $sep . '(.*)' . $sep . '.*$/', $field, $matches)) {
            $subField = $matches[1];
        }
        return $subField;
    }

    /**
     * Get main highlighter pretag
     * @return string
     */
    protected function getPreTag()
    {
        $tags = $this->highlighter->getPreTags();
        return isset($tags[0]) ? $tags[0]: '';
    }

    /**
     * Get main highlighter posttag
     * @return string
     */
    protected function getPostTag()
    {
        $tags = $this->highlighter->getPostTags();
        return isset($tags[0]) ? $tags[0]: '';
    }
}
