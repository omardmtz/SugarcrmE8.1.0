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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\Implement;

use Sugarcrm\Sugarcrm\Elasticsearch\Analysis\AnalysisBuilder;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Mapping;
use Sugarcrm\Sugarcrm\Elasticsearch\Mapping\Property\MultiFieldProperty;
use Sugarcrm\Sugarcrm\Elasticsearch\Exception\MappingException;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\SearchFields;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\GlobalSearch;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\AbstractHandler;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\AnalysisHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\MappingHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\Handler\SearchFieldsHandlerInterface;
use Sugarcrm\Sugarcrm\Elasticsearch\Provider\GlobalSearch\SearchField;

/**
 *
 * Generic Mapping Handler using multi fields
 *
 */
class MultiFieldHandler extends AbstractHandler implements
    AnalysisHandlerInterface,
    MappingHandlerInterface,
    SearchFieldsHandlerInterface
{
    /**
     * Mappings for types using multi field definition
     * @var array
     */
    protected $typesMultiField = array(
        'varchar' => array(
            'gs_string',
            'gs_string_wildcard',
        ),
        'name' => array(
            'gs_string',
            'gs_string_wildcard',
        ),
        'text' => array(
            'gs_string',
            // Disable wildcard search awaiting optimization
            //'gs_text_wildcard',
        ),
        'datetime' => array(
            'gs_datetime',
        ),
        'datetimecombo' => array(
            'gs_datetime',
        ),
        'date' => array(
            'gs_date',
        ),
        'int' => array(
            'gs_integer',
            'gs_string',
            'gs_string_wildcard',
        ),
        'phone' => array(
            'gs_not_analyzed',
            'gs_phone_wildcard',
        ),
        'url' => array(
            'gs_url',
            'gs_url_wildcard',
        ),
        'id' => array(
            'gs_not_analyzed',
        ),
        'exact' => array(
            'gs_string_exact',
        ),
        'longtext' => array(
            'gs_string',
            // Disable wildcard search awaiting optimization
            //'gs_text_wildcard',
        ),
        'htmleditable_tinymce' => array(
            'gs_string',
            // Disable wildcard search awaiting optimization
            //'gs_text_wildcard',
        ),
        'enum' => array(
            'gs_not_analyzed',
        ),
    );

    /**
     * Weighted boost definition
     * @var array
     */
    protected $weightedBoost = array(
        'gs_string_wildcard' => 0.45,
        'gs_text_wildcard' => 0.35,
        'gs_phone_wildcard' => 0.20,
        'gs_url_wildcard' => 0.35,
    );

    /**
     * Highlighter field definitions
     * @var array
     */
    protected $highlighterFields = array(
        '*.gs_not_analyzed' => array(),
        '*.gs_string' => array(),
        '*.gs_string_exact' => array(),
        '*.gs_string_html' => array(),
        '*.gs_string_wildcard' => array(),
        '*.gs_text_wildcard' => array(),
        '*.gs_phone_wildcard' => array(),
    );

    /**
     * Multi field definitions
     * @var array
     */
    protected $multiFieldDefs = [

        /*
         * Mapping which stores the values as a single term. This
         * is the equivalent to the old Elasticsearch behavior for
         * `index: not_analyzed` which is no longer supported.
         */
        'gs_not_analyzed' => [
            'type' => 'keyword',
            'index' => true,
            'store' => true,
        ],

        /*
         * Default string analyzer with full word matching base ond
         * the standard analyzer. This will generate hits on the full
         * words tokenized by the standard analyzer.
         */
        'gs_string' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_string',
            'store' => true,
        ],

        /*
         * String analyzer using ngrams for wildcard matching. The
         * weighting of the hits on this mapping are less than full
         * matches using the default string mapping.
         */
        'gs_string_wildcard' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_string_ngram',
            'search_analyzer' => 'gs_analyzer_string',
            'store' => true,
        ],

        /*
         * Wildcard analyzer for text fields. Because they can become
         * big we use a text_ngram definition.
         */
        'gs_text_wildcard' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_text_ngram',
            'search_analyzer' => 'gs_analyzer_string',
            'store' => true,
        ],

        /*
         * Date field mapping. Date fields are not searchable but are
         * needed to be returned as part of the dataset and to be able
         * to perform facets on. Note that the index cannot be 'no' to
         * return the fields of 'gs_datetime' type in the facets.
         */
        'gs_datetime' => [
            'type' => 'date',
            'format' => 'YYYY-MM-dd HH:mm:ss',
            'store' => false,
        ],
        'gs_date' => [
            'type' => 'date',
            'format' => 'YYYY-MM-dd',
            'store' => false,
        ],

        /*
         * Integer mapping.
         */
        'gs_integer' => [
            'type' => 'integer',
            'index' => false,
            'store' => false,
        ],

        /*
         * Phone mapping. The analyzer supports partial matches using
         * ngrams and transforms every phone number in pure numbers
         * only to be able to search for different formats and still
         * get hits. For example the data source for +32 (475)61.64.28
         * will be stored and analyzed as 32475616428 including ngrams
         * based on this result. When phone number fields are included
         * in the search matching will happen when searching for:
         *      +32 475 61.64.28
         *      (32)475-61-64-28
         *      ...
         */
        'gs_phone_wildcard' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_phone_ngram',
            'search_analyzer' => 'gs_analyzer_phone',
            'store' => true,
        ],

        /*
         * URL analyzer
         */
        'gs_url' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_url',
            'store' => false,
        ],

        /*
         * Wildcard matching for URLs.
         */
        'gs_url_wildcard' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_url_ngram',
            'search_analyzer' => 'gs_analyzer_url',
            'store' => false,
        ],

        /*
         * String analyzer with full word matching base ond
         * the whitespace analyzer. This will generate hits on the full
         * words tokenized by the whitespace analyzer.
         */
        'gs_string_exact' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_string_exact',
            'store' => true,
        ],

        /*
         * Analyzer for html
         */
        'gs_string_html' => [
            'type' => 'text',
            'index' => true,
            'analyzer' => 'gs_analyzer_string_html',
            'store' => true,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function setProvider(GlobalSearch $provider)
    {
        parent::setProvider($provider);

        $provider->addSupportedTypes(array_keys($this->typesMultiField));
        $provider->addWeightedBoosts($this->weightedBoost);
        $provider->addHighlighterFields($this->highlighterFields);
    }

    /**
     * {@inheritdoc}
     */
    public function buildAnalysis(AnalysisBuilder $analysisBuilder)
    {
        $analysisBuilder

            // ngram filter using 1/15
            ->addFilter(
                'gs_filter_ngram_1_15',
                'nGram',
                array('min_gram' => 1, 'max_gram' => 15)
            )

            // ngram filter using 2_15
            ->addFilter(
                'gs_filter_ngram_2_15',
                'nGram',
                array('min_gram' => 2, 'max_gram' => 15)
            )

            // ngram filter using 3_15
            ->addFilter(
                'gs_filter_ngram_3_15',
                'nGram',
                array('min_gram' => 3, 'max_gram' => 15)
            )

            // char filter keeping only numeric values
            ->addCharFilter(
                'gs_char_num_pattern',
                'pattern_replace',
                array('pattern' => '[^\\d]+', 'replacement' => '')
            )

            // base analyzer using standard tokenizer
            ->addCustomAnalyzer(
                'gs_analyzer_string',
                'standard',
                array('lowercase')
            )

            // base ngram analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_string_ngram',
                'standard',
                array('lowercase', 'gs_filter_ngram_1_15')
            )

            // phone analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_phone',
                'whitespace',
                array(),
                array('gs_char_num_pattern')
            )

            // phone ngram analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_phone_ngram',
                'whitespace',
                array('gs_filter_ngram_3_15'),
                array('gs_char_num_pattern')
            )

            // analyzer for text fields with lower tokens
            ->addCustomAnalyzer(
                'gs_analyzer_text_ngram',
                'standard',
                array('lowercase', 'gs_filter_ngram_3_15')
            )

            // url analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_url',
                'uax_url_email',
                array('lowercase')
            )

            // url ngram analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_url_ngram',
                'uax_url_email',
                array('lowercase', 'gs_filter_ngram_3_15')
            )

            // String Analyzer using whitespace tokenizer for exact matching
            ->addCustomAnalyzer(
                'gs_analyzer_string_exact',
                'whitespace',
                array('lowercase')
            )

            // html analyzer
            ->addCustomAnalyzer(
                'gs_analyzer_string_html',
                'standard',
                array('lowercase'),
                array('html_strip')
            )
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildMapping(Mapping $mapping, $field, array $defs)
    {
        // Skip field if no multi field mapping has been defined or no type available
        if (!isset($defs['type']) || !isset($this->typesMultiField[$defs['type']])) {
            return;
        }

        $fieldType = $defs['type'];

        foreach ($this->typesMultiField[$fieldType] as $multiField) {
            if ($property = $this->getMultiFieldProperty($multiField)) {
                $mapping->addModuleField($field, $multiField, $property);

                // Sortable fields also receive a common field to make sorting
                // possible when querying multiple modules at once.
                if (!empty($defs['full_text_search']['sortable'])) {
                    $mapping->addCommonField($field, $multiField, $property);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function buildSearchFields(SearchFields $sfs, $module, $field, array $defs)
    {
        // Skip field if no multi field mapping has been defined or no type available
        if (!isset($defs['type']) || !isset($this->typesMultiField[$defs['type']])) {
            return;
        }

        // Add fields which are based on strings
        foreach ($this->getStringFieldsForType($defs['type']) as $searchField) {
            $sf = new SearchField($module, $field, $defs);
            $sf->setPath([$field, $searchField]);
            $sfs->addSearchField($sf, $searchField);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSupportedTypes()
    {
        return array_keys($this->typesMultiField);
    }

    /**
     * Add additional field to the highlighter
     * @param string $module Module name
     * @param string $field Field name
     * @param array $settings Highlighter settings
     */
    protected function addHighlighterField($module, $field, array $settings = array())
    {
        $this->provider->addHighlighterFields([$field => $settings]);
    }

    /**
     * Get search field list for given field type
     * @param string $type
     * @return array
     */
    protected function getStringFieldsForType($type)
    {
        $list = array();
        foreach ($this->typesMultiField[$type] as $multiFieldDef) {
            if ($this->isStringBased($multiFieldDef)) {
                $list[] = $multiFieldDef;
            }
        }
        return $list;
    }

    /**
     * Check if given multi field definition is string based
     * @param string $multiFieldDef Multi field definition name
     * @return boolean
     */
    protected function isStringBased($multiFieldDef)
    {
        $defs = $this->multiFieldDefs[$multiFieldDef];
        if (isset($defs['type']) && in_array($defs['type'], ['text', 'keyword'])) {
            return true;
        }

        return false;
    }

    /**
     * Get multi field property object
     * @param string $name Multi field property name
     * @throws MappingException
     * @return MultiFieldProperty|false
     */
    protected function getMultiFieldProperty($name)
    {
        if (!isset($this->multiFieldDefs[$name])) {
            throw new MappingException("Unknown multi field definition '{$name}'");
        }

        // Gracefully return false if no explicit mapping is defined
        if (empty($this->multiFieldDefs[$name])) {
            return false;
        }

        if (!isset($this->multiFieldDefs[$name]['type'])) {
            throw new MappingException("Multi field definition '{$name}' missing required type");
        }

        $multiField = new MultiFieldProperty();
        $multiField->setType($this->multiFieldDefs[$name]['type']);
        $multiField->setMapping($this->multiFieldDefs[$name]);

        return $multiField;
    }
}
