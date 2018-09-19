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

namespace Sugarcrm\Sugarcrm\Elasticsearch\Analysis;

use Sugarcrm\Sugarcrm\Elasticsearch\Exception\AnalyzerBuilderException;

/**
 *
 * Analysis builder is used to build all (custom) analysis definitions through
 * the different registered Providers. The analysis settings are composed of:
 *  - Analyzers
 *  - Tokenizers
 *  - Token filters
 *  - Character filters
 *
 *  Note that analysis settings are not shared among the different providers
 *  to avoid collisions. Defining multiple times the same analyzers has no
 *  performance impact. It's advised that every provider prefixes the names
 *  of the different analysis objects it creates.
 *
 */
class AnalysisBuilder
{
    const ANALYSIS = 'analysis';
    const ANALYZER = 'analyzer';
    const CUSTOM_ANALYZER = 'custom';
    const TOKENIZER = 'tokenizer';
    const TOKENFILTER = 'filter';
    const CHARFILTER = 'char_filter';

    /**
     * @var array List of analysis entries
     */
    protected $analysis = array();

    /**
     * Ctor
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Compile analysis settings
     * @return array
     */
    public function compile()
    {
        return array(self::ANALYSIS => $this->analysis);
    }

    /**
     * Add analyzer
     * @param string $name Alias of the analyzer
     * @param string $type Builtin analyzer type
     * @param array $options
     */
    public function addAnalyzer($name, $type, array $options = array())
    {
        $this->addAnalysis(self::ANALYZER, $name, $type, $options);
        return $this;
    }

    /**
     * Add tokenizer
     * @param string $name Alias of the tokenizer
     * @param unknown $type
     * @param array $options
     */
    public function addTokenizer($name, $type, array $options = array())
    {
        $this->addAnalysis(self::TOKENIZER, $name, $type, $options);
        return $this;
    }

    /**
     * Add token filter
     * @param string $name Alias of the token filter
     * @param string $type
     * @param array $options
     */
    public function addFilter($name, $type, array $options = array())
    {
        $this->addAnalysis(self::TOKENFILTER, $name, $type, $options);
        return $this;
    }

    /**
     * Add character filter
     * @param string $name Alias of the character filter
     * @param string $type
     * @param array $options
     */
    public function addCharFilter($name, $type, array $options = array())
    {
        $this->addAnalysis(self::CHARFILTER, $name, $type, $options);
        return $this;
    }

    /**
     * Add custom analyzer
     * @param string $name Alias of the custom analyzer
     * @param string $tokenizer Alias of the tokenizer
     * @param array $filters Optional list of token filters
     * @param array $charFilters Optional list of character filters
     */
    public function addCustomAnalyzer($name, $tokenizer, array $filters = array(), array $charFilters = array())
    {
        $options = array(
            self::TOKENIZER => $tokenizer,
        );

        if ($filters) {
            $options[self::TOKENFILTER] = $filters;
        }

        if ($charFilters) {
            $options[self::CHARFILTER] = $charFilters;
        }

        $this->addAnalysis(self::ANALYZER, $name, self::CUSTOM_ANALYZER, $options);
        return $this;
    }

    /**
     * Wrapper function to expand the analysis settings. Currently the anlysis
     * settings are shared among the different providers. It's important to use
     * unique names within a provider to avoid overwriting analysis settings
     * from a different provider.
     *
     * @param string $base Base key of `$this->analysis`
     * @param string $name Alias to add
     * @param string $type
     * @param array $options
     * @throws \Sugarcrm\Sugarcrm\Elasticsearch\Exception\AnalyzerBuilderException
     */
    private function addAnalysis($base, $name, $type, array $options)
    {
        if (isset($this->analysis[$base][$name])) {
            throw new AnalyzerBuilderException("Cannot redeclare $base '{$name}'");
        }
        $settings = array_merge($options, array('type' => $type));
        $this->analysis[$base][$name] = $settings;
    }

    /**
     * Initialize object
     */
    private function init()
    {
        $this->analysis = array(
            self::ANALYZER => array(),
            self::TOKENIZER => array(),
            self::TOKENFILTER => array(),
            self::CHARFILTER => array(),
        );
    }
}
