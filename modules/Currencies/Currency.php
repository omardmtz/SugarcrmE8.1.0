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
/**
 * Currency
 *
 * This is the Currency module for obtaining and manipulating currency objects.
 *
 */
class Currency extends SugarBean
{
    // Stored fields
    var $id;
    var $iso4217;
    var $name;
    var $status;
    var $conversion_rate;
    var $deleted;
    var $date_entered;
    var $date_modified;
    var $symbol;
    var $hide = '';
    var $unhide = '';

    var $table_name = "currencies";
    var $object_name = "Currency";
    var $module_dir = "Currencies";
    var $new_schema = true;

    var $disable_num_format = true;


    /**
     * class constructor
     *
     * @access public
     */
    public function __construct()
    {
        parent::__construct();
        global $app_strings, $current_user, $sugar_config, $locale;
        $this->field_defs['hide'] = array('name'=>'hide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);
        $this->field_defs['unhide'] = array('name'=>'unhide', 'source'=>'non-db', 'type'=>'varchar','len'=>25);
        $this->disable_row_level_security =true;
    }

    /**
     * convertToDollar
     *
     * This method accepts a currency amount and converts it to the US Dollar amount
     * This method is deprecated, see convertToBase()
     *
     * @deprecated
     * @param  float $amount    amount to convert to US Dollars
     * @param  int   $precision rounding precision scale
     * @return float  currency  value in US Dollars from conversion
     */
	function convertToDollar($amount, $precision = 6) {
		return $this->convertToBase($amount, $precision);
	}

    /**
     * convertFromDollar
     *
     * This method accepts a US Dollar amount and returns a currency amount
     * with the conversion rate applied to it.
     * This method is deprecated, see convertFromBase()
     *
     * @deprecated
     * @param  float $amount    currency amount in US Dollars
     * @param  int   $precision rounding precision scale
     * @return float  currency  value from US Dollar conversion
     */
    function convertFromDollar($amount, $precision = 6) {
        return $this->convertFromBase($amount, $precision);
    }

    /**
     * convert amount to base currency
     *
     * @param  float $amount    amount to convert to US Dollars
     * @param  int   $precision rounding precision scale
     * @return float  currency  value in US Dollars from conversion
     */
    function convertToBase($amount, $precision = 6) {
        $amount = ($amount == null) ? 0 : $amount;
        return SugarCurrency::convertWithRate(str_replace($this->symbol, '', $amount), $this->conversion_rate, 1.0, $precision);
    }

    /**
     * convert amount from base currency
     *
     * @param  float $amount    currency amount in US Dollars
     * @param  int   $precision rounding precision scale
     * @return float  currency  value from US Dollar conversion
     */
    function convertFromBase($amount, $precision = 6) {
        $amount = ($amount == null) ? 0 : $amount;
        return SugarCurrency::convertWithRate(str_replace($this->symbol, '', $amount), 1.0, $this->conversion_rate, $precision);
    }


    /**
     * getDefaultCurrencyName
     *
     * Returns the default currency name as defined in application
     *
     * @return string value of default currency name
     */
    function getDefaultCurrencyName()
    {
        global $sugar_config;
        return isset($sugar_config['default_currency_name'])?$sugar_config['default_currency_name']:'';
    }

    /**
     * getDefaultCurrencySymbol
     *
     * Returns the default currency symbol in application
     *
     * @return string value of default currency symbol (e.g. $)
     */
    function getDefaultCurrencySymbol()
    {
        global $sugar_config;
        return isset($sugar_config['default_currency_symbol'])?$sugar_config['default_currency_symbol']:'';
    }

    /**
     * getDefaultISO4217
     *
     * Returns the default ISO 4217 standard currency code value
     *
     * @return string value for the ISO 4217 standard code (e.g. EUR)
     */
    function getDefaultISO4217()
    {
        global $sugar_config;
        return isset($sugar_config['default_currency_iso4217'])?$sugar_config['default_currency_iso4217']:'';
    }

    /**
     * retrieveIDBySymbol
     *
     * Returns the id value for given currency symbol in currencies table
     * and currency entry for symbol is not set to deleted.
     *
     * @param  $symbol currency symbol
     * @return string  id value for symbol defined in currencies table,
     *                 blank value for nothing found
     */
    function retrieveIDBySymbol($symbol)
    {
        $query = "SELECT id FROM currencies WHERE symbol= ? AND deleted= ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($symbol, 0));

        $row = $stmt->fetchColumn();
        if ($row) {
            return $row;
        }

        if ($symbol == $this->getDefaultCurrencySymbol()) {
            return '-99';
        }

        return '';
     }

    /**
     * retrieveIDByISO
     *
     * Returns the id value for given currency iso4217 in Currencies table
     * and currency entry for ISO is not set to deleted.
     *
     * @param  $ISO   iso4217 value
     * @return string id value for symbol defined in currencies table,
     *                blank value for nothing found
     */
    public function retrieveIDByISO($iso)
    {
        $query = "SELECT id FROM currencies WHERE iso4217= ? AND deleted= ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($iso, 0));

        $row = $stmt->fetchColumn();
        if ($row) {
            return $row;
        }

        if ($iso == $this->getDefaultISO4217()) {
            return '-99';
        }

        return '';
    }


    /**
     * list_view_parse_additional_sections
     *
     * @param  object $list_form
     * @return object $list_form with merged currency id
     */
    function list_view_parse_additional_sections(&$list_form)
    {
        global $isMerge;

        if(isset($isMerge) && $isMerge && $this->id != '-99'){
            $list_form->assign('PREROW', '<input name="mergecur[]" type="checkbox" value="'.$this->id.'">');
        }
        return $list_form;
    }

    /**
     * retrieveIDByName
     *
     * Returns the id value for given currency name in Currencies table
     * and currency entry for name is not set to deleted.
     *
     * @param  string $name  currency name
     * @return string id value for symbol defined in currencies table,
     *                blank value for nothing found
     */
    function retrieveIDByName($name)
    {
        $query = "select id from currencies where name= ? AND deleted= ?";
        $conn = $this->db->getConnection();
        $stmt = $conn->executeQuery($query, array($name, 0));

        $row = $stmt->fetchColumn();
        if ($row) {
            return $row;
        }

        if ($name == $this->getDefaultCurrencyName()) {
            return '-99';
        }

        return '';
    }

    /**
     * retrieve_id_by_name
     *
     * deprecated, see retrieveIDByName
     * @deprecated
     */
    function retrieve_id_by_name($name)
    {
        $this->retrieveIDByName($name);
    }

    /**
     * retrieve
     *
     * returns currency object for given id, or base if none given
     *
     * @param  string   $id      currency id
     * @param  boolean  $encode
     * @param  boolean  $deleted
     * @return object   currency object, base currency if id is not found
     */
    public function retrieve($id = -1, $encode = true, $deleted = true)
    {
        if(empty($id) || $id == '-99') {
            $this->setBaseCurrency();
        } else {
            parent::retrieve($id, $encode, $deleted);
        }
        if(!isset($this->name) || $this->deleted == 1){
            $this->setBaseCurrency();
        }
        return $this;
     }

    /**
     * set the current object to the system base currency
     */
    function setBaseCurrency()
    {
        $this->name =     $this->getDefaultCurrencyName();
        $this->symbol = $this->getDefaultCurrencySymbol();
        $this->id = '-99';
        $this->conversion_rate = '1.000000';
        $this->iso4217 = $this->getDefaultISO4217();
        $this->deleted = 0;
        $this->status = 'Active';
        $this->hide = '<!--';
        $this->unhide = '-->';
    }

    /**
     * Method for returning the currency symbol, must return chr(2) for the € symbol
     * to display correctly in PDFs
     *
     * @return string $symbol otherwise chr(2) for euro symbol
     */
    function getPdfCurrencySymbol()
    {
        if($this->symbol == '&#8364;' || $this->symbol == '€')
            return chr(2);
        return $this->symbol;
    }

    /**
     * @return array $data list view
     */
    function get_list_view_data()
    {
        $this->conversion_rate = format_number($this->conversion_rate, 10, 10);
        $data = parent::get_list_view_data();
        return $data;
    }

    /**
     * @param  bool $check_notify
     * @return String
     */
    function save($check_notify = false)
    {
        sugar_cache_clear('currency_list');

        $return = parent::save($check_notify);

        // The per-module cache doesn't need to be cleared here
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_CURRENCIES));
        
        return $return;
    }

    /**
     * Same as SugarBean::mark_deleted except clears api cache.
     * @param $id
     */
    public function mark_deleted($id)
    {
        sugar_cache_clear('currency_list');

        $return = parent::mark_deleted($id);

        // The per-module cache doesn't need to be cleared here
        MetaDataManager::refreshSectionCache(array(MetaDataManager::MM_CURRENCIES));
    }

    /**
     * Return current user's currency setting or -99
     * @return string
     */
    public static function getCurrentCurrency()
    {
        if(!empty($GLOBALS['current_user'])) {
            $currency_id = $GLOBALS['current_user']->getPreference('currency');
        }
        if(empty($currency_id)) {
            $currency_id = '-99';
        }
        return $currency_id;
    }

    /**
     * Retrieve currency for current user
     *
     * @return object currency object
     */
    public static function getUserCurrency()
    {
        $currency_id = self::getCurrentCurrency();
        $currency = BeanFactory::getBean('Currencies', $currency_id);
        return $currency;
    }

    /**
     * Return a list of currency names with their record ids as the keys
     *
     * This is used for the currency_name vardefs via the API
     *
     * @return array
     */
    public static function getCurrencies()
    {

        $currency = new ListCurrency();
        $currency->lookupCurrencies();

        $currencyList = array();
        foreach ($currency->list as $item) {
            $currencyList[$item->id] = $item->name;
        }

        return $currencyList;
    }

    /**
     * Return a list of currency ids with their associated symbols attached
     *
     * This is used for the currency_symbol vardefs via the API
     *
     * @return array
     */
    public static function getCurrencySymbols()
    {

        $currency = new ListCurrency();
        $currency->lookupCurrencies();

        $currencyList = array();
        foreach ($currency->list as $item) {
            $currencyList[$item->id] = $item->symbol;
        }

        return $currencyList;
    }
} // end currency class

/**
 * currency_format_number (deprecated, use SugarCurrency class)
 *
 * This method is a wrapper designed exclusively for formatting currency values
 * with the assumption that the method caller wants a currency formatted value
 * matching his/her user preferences(if set) or the system configuration defaults
 *(if user preferences are not defined).
 *
 * @deprecated
 * @param float $amount The amount to be formatted
 * @param array $params Optional parameters(see @format_number)
 * @return String representation of amount with formatting applied
 */
function currency_format_number($amount, $params = array())
{

    global $locale;
    if(isset($params['round']) && is_int($params['round'])){
        $real_round = $params['round'];
    }else{
        $real_round = $locale->getPrecedentPreference('default_currency_significant_digits');
    }
    if(isset($params['decimals']) && is_int($params['decimals'])){
        $real_decimals = $params['decimals'];
    }else{
        $real_decimals = $locale->getPrecedentPreference('default_currency_significant_digits');
    }
    $real_round = $real_round == '' ? 0 : $real_round;
    $real_decimals = $real_decimals == '' ? 0 : $real_decimals;

    $showCurrencySymbol = $locale->getPrecedentPreference('default_currency_symbol') != '' ? true : false;
    if($showCurrencySymbol && !isset($params['currency_symbol'])) {
       $params["currency_symbol"] = true;
    }
    return format_number($amount, $real_round, $real_decimals, $params);

}

/**
 * format_number(deprecated, use SugarCurrency class)
 *
 * This method accepts an amount and formats it given the user's preferences.
 * Should the values set in the user preferences be invalid then it will
 * apply the system wide Sugar configuration values.  Calls to
 * getPrecendentPreference() method in Localization.php are made that
 * handle this logic.
 *
 * Going forward with Sugar 4.5.0e+ implementations, users of this class should
 * simple call this function with $amount parameter and leave it to the
 * class to locate and apply the appropriate formatting.
 *
 * One of the problems is that there is considerable legacy code that is using
 * this method for non currency formatting.  In other words, the format_number
 * method may be called to just display a number like 1,000 formatted appropriately.
 *
 * Also, issues about responsibilities arise.  Currently the callers of this function
 * are responsible for passing in the appropriate decimal and number rounding digits
 * as well as parameters to control displaying the currency symbol or not.
 *
 * @deprecated
 * @param $amount The currency amount to apply formatting to
 * @param $round Integer value for number of places to round to
 * @param $decimals Integer value for number of decimals to round to
 * @param $params Array of additional parameter values
 *
 *
 * The following are passed in as an array of params:
 *        boolean $params['currency_symbol'] - true to display currency symbol
 *        boolean $params['convert'] - true to convert from USD dollar
 *        boolean $params['percentage'] - true to display % sign
 *        boolean $params['symbol_space'] - true to have space between currency symbol and amount
 *        String  $params['symbol_override'] - string to over default currency symbol
 *        String  $params['type'] - pass in 'pdf' for pdf currency symbol conversion
 *        String  $params['currency_id'] - currency_id to retreive, defaults to current user
 *        String  $params['human'] - formatting that truncates the first thousands and appends "k"
 * @return String formatted currency value
 * @see include/Localization/Localization.php
 */
function format_number($amount, $round = null, $decimals = null, $params = array()) {
    global $app_strings, $current_user, $sugar_config, $locale;
    static $current_users_currency = null;
    static $last_override_currency = null;
    static $override_currency_id = null;
    static $currency;

    $seps = get_number_seperators();
    $num_grp_sep = $seps[0];
    $dec_sep = $seps[1];

    // cn: bug 8522 - sig digits not honored in pdfs
    if(is_null($decimals)) {
        $decimals = $locale->getPrecision();
    }
    if(is_null($round)) {
        $round = $locale->getPrecision();
    }

    // only create a currency object if we need it
    if((!empty($params['currency_symbol']) && $params['currency_symbol']) ||
      (!empty($params['convert']) && $params['convert']) ||
      (!empty($params['currency_id']))) {
               // if we have an override currency_id
               if(!empty($params['currency_id'])) {
                   if($override_currency_id != $params['currency_id']) {
                       $override_currency_id = $params['currency_id'];
                       $currency = BeanFactory::getBean('Currencies', $override_currency_id);
                       $last_override_currency = $currency;
                   } else {
                       $currency = $last_override_currency;
                   }

               } elseif(!isset($current_users_currency)) { // else use current user's
                   $currency_id = $current_user->getPreference('currency');
                   if(empty($currency_id)) {
                       $currency_id = '-99';
                   }
                $currency = $current_users_currency = BeanFactory::getBean('Currencies', $currency_id);
            }
    }
    if(!empty($params['convert']) && $params['convert']) {
        $amount = $currency->convertFromDollar($amount, 6);
    }

    if(!empty($params['currency_symbol']) && $params['currency_symbol']) {
        if(!empty($params['symbol_override'])) {
            $symbol = $params['symbol_override'];
        }
        elseif(!empty($params['type']) && $params['type'] == 'pdf') {
            $symbol = $currency->getPdfCurrencySymbol();
            $symbol_space = false;
        } else {
            if(empty($currency->symbol))
                $symbol = $currency->getDefaultCurrencySymbol();
            else
                $symbol = $currency->symbol;
            $symbol_space = true;
        }
    } else {
        $symbol = '';
    }

    if(isset($params['charset_convert'])) {
        $symbol = $locale->translateCharset($symbol, 'UTF-8', $locale->getExportCharset());
    }

    if(empty($params['human'])) {
       $amount = number_format(round($amount, $round), $decimals, $dec_sep, $num_grp_sep);
       $amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
    } else {
        // If amount is more greater than a thousand(positive or negative)
        if(strpos($amount, '.') > 0) {
           $checkAmount = strlen(substr($amount, 0, strpos($amount, '.')));
        }

        if($checkAmount >= 1000 || $checkAmount <= -1000) {
            $amount = round(($amount / 1000), 0);
            $amount = number_format($amount, 0, $dec_sep, $num_grp_sep); // add for SI bug 52498
            $amount = $amount . 'k';
            $amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
        } else {
            $amount = format_place_symbol($amount, $symbol,(empty($params['symbol_space']) ? false : true));
        }
    }

    if(!empty($params['percentage']) && $params['percentage']) $amount .= $app_strings['LBL_PERCENTAGE_SYMBOL'];
    return $amount;

} //end function format_number

/**
 * (deprecated, use SugarCurrency class)
 *
 * @deprecated
 * @param string $amount
 * @param string $symbol
 * @param string $symbol_space
 * @return string
 */
function format_place_symbol($amount, $symbol, $symbol_space) {
    if($symbol != '') {
        if($symbol_space == true) {
            $amount = $symbol . '&nbsp;' . $amount;
        } else {
            $amount = $symbol . $amount;
        }
    }
    return $amount;
}

/**
 * @param $string
 * @param bool $useBaseCurrency default user locale
 * @return float|string
 */
function unformat_number($string, $useBaseCurrency = false)
{
    static $currency = null;
    if(!isset($currency)) {
        global $current_user;
        if(!$useBaseCurrency && !empty($current_user->id)){
            $currency_id = $current_user->getPreference('currency');
        }
        if(empty($currency_id)) {
            $currency_id = '-99';
        }
        $currency = BeanFactory::getBean('Currencies', $currency_id);
    }

    $seps = get_number_seperators();
    // remove num_grp_sep and replace decimal separator with decimal
    $string = trim(str_replace(array($seps[0], $seps[1], $currency->symbol), array('', '.', ''), $string));
    if(preg_match('/^[+-]?\d(\.\d+)?[Ee]([+-]?\d+)?$/', $string)) $string = sprintf("%.0f", $string);//for scientific number format. After round(), we may get this number type.
    preg_match('/[\-\+]?[0-9\.]*/', $string, $string);

    $out_number = trim($string[0]);
    if ( $out_number == '' ) {
        return '';
    } else {
        return (float)$out_number;
    }
}

/**
 * deprecated use format_number() above
 * @deprecated
 * @param $amount
 * @param bool $for_display
 * @return string
 */
function format_money($amount, $for_display = true)
{
    // This function formats an amount for display.
    // Later on, this should be converted to use proper thousand and decimal seperators
    // Currently, it stays closer to the existing format, and just rounds to two decimal points
    if(isset($amount)) {
        if($for_display) {
            return sprintf("%0.02f",$amount);
        } else {
            // If it's an editable field, don't use a thousand seperator.
            // Or perhaps we will want to, but it doesn't matter right now.
            return sprintf("%0.02f",$amount);
        }
    } else {
        return;
    }
}

/**
 * Returns user/system preference for number grouping separator character(default ",") and the decimal separator
 *(default ".").  Special case: when num_grp_sep is ".", it will return NULL as the num_grp_sep.
 * @param boolean $reset_sep
 * @return array Two element array, first item is num_grp_sep, 2nd item is dec_sep
 */
function get_number_seperators($reset_sep = false)
{
    global $current_user, $sugar_config;

    static $dec_sep = null;
    static $num_grp_sep = null;

    // This is typically only used during unit-tests
    // TODO: refactor this. unit tests should not have static dependencies
    if ($reset_sep)
    {
        $dec_sep = $num_grp_sep = null;
    }

    if ($dec_sep == null)
    {
        $dec_sep = $sugar_config['default_decimal_seperator'];
        if (!empty($current_user->id))
        {
            $user_dec_sep = $current_user->getPreference('dec_sep');
            $dec_sep = (empty($user_dec_sep) ? $sugar_config['default_decimal_seperator'] : $user_dec_sep);
        }
    }

    if ($num_grp_sep == null)
    {
        $num_grp_sep = $sugar_config['default_number_grouping_seperator'];
        if (!empty($current_user->id))
        {
             $user_num_grp_sep = $current_user->getPreference('num_grp_sep');
            $num_grp_sep = (empty($user_num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $user_num_grp_sep);
        }
    }

    return array($num_grp_sep, $dec_sep);
}

/**
 * toString
 *
 * Utility function to print out some information about Currency instance.
 *
 * @deprecated
 * @param boolean $echo
 * @return string $s
 */
function toString($echo = true)
{
    $s = "\$m_currency_round=$m_currency_round \n" .
         "\$m_currency_decimal=$m_currency_decimal \n" .
         "\$m_currency_symbol=$m_currency_symbol \n" .
         "\$m_currency_iso=$m_currency_iso \n" .
         "\$m_currency_name=$m_currency_name \n";

    if($echo) {
       echo $s;
    }

    return $s;
}

/**
 * @param object $focus
 * @param string $field
 * @param string $value
 * @param string $view
 * @return string
 */
function getCurrencyDropDown($focus, $field='currency_id', $value='', $view='DetailView')
{
    $view = ucfirst($view);
    if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate' || $view == 'ConvertLead'){
        if ( isset($_REQUEST[$field]) && !empty($_REQUEST[$field]) ) {
            $value = $_REQUEST[$field];
        } elseif ( empty($focus->id) ) {
            $value = $GLOBALS['current_user']->getPreference('currency');
            if ( empty($value) ) {
                // -99 is the system default currency
                $value = -99;
            }
        }
        $currency_fields = array();
        //Bug 18276 - Fix for php 5.1.6
        $defs=$focus->field_defs;
        //
        foreach($defs as $name=>$key){
            if($key['type'] == 'currency'){
                $currency_fields[]= $name;
            }
        }
        $currency = new ListCurrency();
        $selectCurrency = $currency->getSelectOptions($value);

        $currency->setCurrencyFields($currency_fields);
        $html = '<select name="';
        // If it's a lead conversion (ConvertLead view), add the module_name before the $field
        if ($view == "ConvertLead") {
            $html .= $focus->module_name;
        }
        $html .= $field. '" id="' . $field  . '_select" ';
        if($view != 'MassUpdate')
            $html .= 'onchange="CurrencyConvertAll(this.form);"';
        $html .= '>'. $selectCurrency . '</select>';
        if($view != 'MassUpdate')
            $html .= $currency->getJavascript();
        return $html;
    }else{

        $currency = BeanFactory::getBean('Currencies', $value);
        return $currency->name;
    }

}

/**
 * @deprecated
 * @see Currency::getCurrencies
 * @param object $focus
 * @param string $field
 * @param string $value
 * @param string $view
 * @return string
 */
function getCurrencyNameDropDown($focus, $field='currency_name', $value='', $view='DetailView')
{
    if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate'){
        $currency_fields = array();
        //Bug 18276 - Fix for php 5.1.6
        $defs=$focus->field_defs;
        //
        foreach($defs as $name=>$key){
            if($key['type'] == 'currency'){
                $currency_fields[]= $name;
            }
        }
        $currency = new ListCurrency();
        $currency->lookupCurrencies();
        $listitems = array();
        foreach ( $currency->list as $item )
            $listitems[$item->name] = $item->name;
        return '<select name="'.$field.'" id="'.$field.'" />'.
            get_select_options_with_id($listitems,$value).'</select>';
    }else{

        if ( isset($focus->currency_id) ) {
            $currency_id = $focus->currency_id;
        } else {
            $currency_id = '-99';
        }
        $currency = BeanFactory::getBean('Currencies', $currency_id);
        return $currency->name;
    }
}

/**
 * @deprecated
 * @see Currency::getCurrencySymbols
 * @param object $focus
 * @param string $field
 * @param string $value
 * @param string $view
 * @return string
 */
function getCurrencySymbolDropDown($focus, $field='currency_name', $value='', $view='DetailView')
{
    if($view == 'EditView' || $view == 'MassUpdate' || $view == 'QuickCreate'){
        $currency_fields = array();
        //Bug 18276 - Fix for php 5.1.6
        $defs=$focus->field_defs;
        //
        foreach($defs as $name=>$key){
            if($key['type'] == 'currency'){
                $currency_fields[]= $name;
            }
        }
        $currency = new ListCurrency();
        $currency->lookupCurrencies();
        $listitems = array();
        foreach ( $currency->list as $item )
            $listitems[$item->symbol] = $item->symbol;
        return '<select name="'.$field.'" id="'.$field.'" />'.
            get_select_options_with_id($listitems,$value).'</select>';
    }else{

        if ( isset($focus->currency_id) ) {
            $currency_id = $focus->currency_id;
        } else {
            $currency_id = '-99';
        }
        $currency = BeanFactory::getBean('Currencies', $currency_id);
        return $currency->name;
    }
}

/**
 * Returns a list of all currencies as array of 'id' => 'name' elements
 * @deprecated
 * @see Currency::getCurrencies
 * @return array
 */
function getCurrencyDropDownList()
{
    return Currency::getCurrencies();
}
