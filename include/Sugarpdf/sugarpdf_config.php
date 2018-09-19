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

foreach(SugarAutoLoader::existingCustom('include/Sugarpdf/sugarpdf_default.php') as $file) {
    require $file;
}
// set alternative config file
if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {

    /*
     *  Installation path of TCPDF
     */
    define ("K_PATH_MAIN", $sugarpdf_default["K_PATH_MAIN"]);
    /**
     * URL path to tcpdf installation folder
     */
    define ("K_PATH_URL", $sugarpdf_default["K_PATH_URL"]);
    /**
     * custom path for PDF fonts (Use for non core added fonts)
     */
    define ("K_PATH_CUSTOM_FONTS", $sugarpdf_default["K_PATH_CUSTOM_FONTS"]);
     /**
     * path for PDF fonts
     */
    define ("K_PATH_FONTS", $sugarpdf_default["K_PATH_FONTS"]);
    /**
     * cache directory for temporary files (full path)
     */
    define ("K_PATH_CACHE", $sugarpdf_default["K_PATH_CACHE"]);
    /**
     * cache directory for temporary files (url path)
     */
    define ("K_PATH_URL_CACHE", $sugarpdf_default["K_PATH_URL_CACHE"]);

    /*
     * Custom path for images (use for loaded logos)
     */
    define ("K_PATH_CUSTOM_IMAGES", $sugarpdf_default["K_PATH_CUSTOM_IMAGES"]);
    /*
     * Default path for images
     */
    define ("K_PATH_IMAGES", $sugarpdf_default["K_PATH_IMAGES"]);
    /*
     * Blank image
     */
    define ("K_BLANK_IMAGE", $sugarpdf_default["K_BLANK_IMAGE"]);
    /*
     * The format used for pages.
     * It can be either one of the following values (case insensitive)
     * or a custom format in the form of a two-element array containing
     * the width and the height (expressed in the unit given by unit).
     * 4A0, 2A0, A0, A1, A2, A3, A4 (default), A5, A6, A7, A8, A9, A10,
     * B0, B1, B2, B3, B4, B5, B6, B7, B8, B9, B10, C0, C1, C2, C3, C4,
     * C5, C6, C7, C8, C9, C10, RA0, RA1, RA2, RA3, RA4, SRA0, SRA1,
     * SRA2, SRA3, SRA4, LETTER, LEGAL, EXECUTIVE, FOLIO.
     */
    defineFromUserPreference ("PDF_PAGE_FORMAT", $sugarpdf_default["PDF_PAGE_FORMAT"]);
    define("PDF_PAGE_FORMAT_LIST", $sugarpdf_default["PDF_PAGE_FORMAT_LIST"]);
    /*
     * page orientation. Possible values are (case insensitive):P or Portrait (default), L or Landscape.
     */
    defineFromUserPreference ("PDF_PAGE_ORIENTATION", $sugarpdf_default["PDF_PAGE_ORIENTATION"]);
    define("PDF_PAGE_ORIENTATION_LIST", $sugarpdf_default["PDF_PAGE_ORIENTATION_LIST"]);
    /*
     * Defines the creator of the document. This is typically the name of the application that generates the PDF.
     */
    defineFromConfig("PDF_CREATOR", $sugarpdf_default["PDF_CREATOR"]);
    /*
     * Defines the author of the document.
     */
    defineFromConfig("PDF_AUTHOR", $sugarpdf_default["PDF_AUTHOR"]);
     /**
     * header title
     */
    defineFromConfig("PDF_HEADER_TITLE", $sugarpdf_default["PDF_HEADER_TITLE"]);
     /**
     * header description string
     */
    defineFromConfig("PDF_HEADER_STRING", $sugarpdf_default["PDF_HEADER_STRING"]);
    /**
     * image logo for the default Header
     */
    defineFromConfig("PDF_HEADER_LOGO", $sugarpdf_default["PDF_HEADER_LOGO"]);
    /**
     * header logo image width [mm]
     */
    defineFromConfig("PDF_HEADER_LOGO_WIDTH", $sugarpdf_default["PDF_HEADER_LOGO_WIDTH"]);
        /**
     * image logo for the default Header
     */
    defineFromConfig("PDF_SMALL_HEADER_LOGO", $sugarpdf_default["PDF_SMALL_HEADER_LOGO"]);
    /**
     * header logo image width [mm]
     */
    defineFromConfig("PDF_SMALL_HEADER_LOGO_WIDTH", $sugarpdf_default["PDF_SMALL_HEADER_LOGO_WIDTH"]);

    /**
     *  document unit of measure [pt=point, mm=millimeter, cm=centimeter, in=inch]
     */
    defineFromConfig('PDF_UNIT', $sugarpdf_default["PDF_UNIT"]);

    /**
     * header margin
     */
    defineFromUserPreference ('PDF_MARGIN_HEADER', $sugarpdf_default["PDF_MARGIN_HEADER"]);

    /**
     * footer margin
     */
    defineFromUserPreference ('PDF_MARGIN_FOOTER', $sugarpdf_default["PDF_MARGIN_FOOTER"]);

    /**
     * top margin
     */
    defineFromUserPreference ('PDF_MARGIN_TOP', $sugarpdf_default["PDF_MARGIN_TOP"]);

    /**
     * bottom margin
     */
    defineFromUserPreference ('PDF_MARGIN_BOTTOM', $sugarpdf_default["PDF_MARGIN_BOTTOM"]);

    /**
     * left margin
     */
    defineFromUserPreference ('PDF_MARGIN_LEFT', $sugarpdf_default["PDF_MARGIN_LEFT"]);

    /**
     * right margin
     */
    defineFromUserPreference ('PDF_MARGIN_RIGHT', $sugarpdf_default["PDF_MARGIN_RIGHT"]);

    /**
     * main font name
     */
    defineFromUserPreference ('PDF_FONT_NAME_MAIN', $sugarpdf_default["PDF_FONT_NAME_MAIN"]);

    /**
     * main font size
     */
    defineFromUserPreference ("PDF_FONT_SIZE_MAIN", $sugarpdf_default["PDF_FONT_SIZE_MAIN"]);
    /**
     * data font name
     */
    defineFromUserPreference ('PDF_FONT_NAME_DATA', $sugarpdf_default["PDF_FONT_NAME_DATA"]);

    /**
     * data font size
     */
    defineFromUserPreference ('PDF_FONT_SIZE_DATA', $sugarpdf_default["PDF_FONT_SIZE_DATA"]);

    /**
     * Ratio used to scale the images
     */
    defineFromConfig('PDF_IMAGE_SCALE_RATIO', $sugarpdf_default["PDF_IMAGE_SCALE_RATIO"]);

    /**
     * magnification factor for titles
     */
    defineFromConfig('HEAD_MAGNIFICATION', $sugarpdf_default["HEAD_MAGNIFICATION"]);

    /**
     * height of cell repect font height
     */
    defineFromConfig('K_CELL_HEIGHT_RATIO', $sugarpdf_default["K_CELL_HEIGHT_RATIO"]);

    /**
     * title magnification respect main font size
     */
    defineFromConfig('K_TITLE_MAGNIFICATION', $sugarpdf_default["K_TITLE_MAGNIFICATION"]);

    /**
     * reduction factor for small font
     */
    defineFromConfig('K_SMALL_RATIO', $sugarpdf_default["K_SMALL_RATIO"]);

}
// Sugarpdf define
/**
 * PDF class use to generate pdf : EZPDF or TCPDF
 */
defineFromConfig("PDF_CLASS", $sugarpdf_default["PDF_CLASS"]);
/**
 * Enable or not the EZPDF class (enable for upgraded system disable otherwise) : 1 or 0
 */
defineFromConfig("PDF_ENABLE_EZPDF", $sugarpdf_default["PDF_ENABLE_EZPDF"]);
/**
 * Default file name for the generated pdf file.
 */
defineFromConfig("PDF_FILENAME", $sugarpdf_default["PDF_FILENAME"]);

/**
 * Title of the document
 */
defineFromConfig("PDF_TITLE", $sugarpdf_default["PDF_TITLE"]);

/**
 * Keywords of the PDF document
 */
defineFromConfig("PDF_KEYWORDS", $sugarpdf_default["PDF_KEYWORDS"]);

/**
 * Subject of the PDF document
 */
defineFromConfig("PDF_SUBJECT", $sugarpdf_default["PDF_SUBJECT"]);

/**
 * Compression of the PDF Document
 */
defineFromConfig("PDF_COMPRESSION", $sugarpdf_default["PDF_COMPRESSION"]);

/**
 * Quality of the JPEG images (0 to 100)
 */
defineFromConfig("PDF_JPEG_QUALITY", $sugarpdf_default["PDF_JPEG_QUALITY"]);

/**
 * PDF version of the PDF document
 */
defineFromConfig("PDF_PDF_VERSION", $sugarpdf_default["PDF_PDF_VERSION"]);

/**
 * Set document protection (available are: copy, print, modify, annot-forms. Seperate with a coma)
 */
defineFromConfig("PDF_PROTECTION", $sugarpdf_default["PDF_PROTECTION"]);

/**
 * User password to view the document. If empty no password
 */
defineFromConfig("PDF_USER_PASSWORD", $sugarpdf_default["PDF_USER_PASSWORD"]);

/**
 * master password to get full access.
 */
defineFromConfig("PDF_OWNER_PASSWORD", $sugarpdf_default["PDF_OWNER_PASSWORD"]);

/**
 * Default ACL access value for the generation of a PDF (detail, list, edit, export)
 */
defineFromConfig("PDF_ACL_ACCESS", $sugarpdf_default["PDF_ACL_ACCESS"]);

/**
 * Available encoding tables when adding a new font
 */
define("PDF_ENCODING_TABLE_LIST", $sugarpdf_default["PDF_ENCODING_TABLE_LIST"]);

/**
 * Available encoding tables when adding a new font (Label)
 */
define("PDF_ENCODING_TABLE_LABEL_LIST", $sugarpdf_default["PDF_ENCODING_TABLE_LABEL_LIST"]);

if (!defined('K_TCPDF_EXTERNAL_CONFIG')) {
    define("K_TCPDF_EXTERNAL_CONFIG", true);
}

/**
 * Function to define a sugarpdf seeting from the admin application settings (config table).
 * This function use the default value if there is nothing in the table.
 * @param $value    settings to search
 * @param $default  default value
 */
function defineFromConfig($value, $default){
    $lowerValue = strtolower($value);
    $focus = Administration::getSettings();
    if(isset($focus->settings["sugarpdf_".$lowerValue])){
        define($value, $focus->settings["sugarpdf_".$lowerValue]);
    }else{
        define($value, $default);
    }
}

/**
 * This function define a Sugarpdf setting from the user preferences.
 * This function use the default value if there is no preference.
 * If SUGARPDF_USE_DEFAULT_SETTINGS is define the function will always
 * use the default value.
 * SUGARPDF_USE_FOCUS is use to load the preference of the none current user. To use
 * this constant you have to define a global variable $focus_user.
 *
 * @param $value    settings to search
 * @param $default  default value
 */
function defineFromUserPreference($value, $default){
    global $focus_user, $current_user;
    $lowerValue = strtolower($value);
    if(defined('SUGARPDF_USE_FOCUS')){
        $pref = $focus_user->getPreference("sugarpdf_".$lowerValue);
    }else{
        $pref = $current_user->getPreference("sugarpdf_".$lowerValue);
    }
    if(strpos($value, "PDF_FONT_NAME_") !== false){
        $fontManager = new FontManager();
        $fontManager->listFontFiles();
        if(!isset($fontManager->fontList[$pref]) || !$fontManager->fontFileExist($fontManager->fontList[$pref]['filename'])){
            $pref = $default;
        }
    }
    if(isset($pref) && !defined('SUGARPDF_USE_DEFAULT_SETTINGS')){
        define($value, $pref);
    }else{
        define($value, $default);
    }

}
