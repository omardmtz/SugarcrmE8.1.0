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
 * Polymorphic buckets - place any item in a report_cache
 */
class ReportCache {

	// public attributes
	public $id;
	public $contents;
	public $contents_array;
	public $new_with_id = false;
	public $date_modified;
	public $report_options;
	public $report_options_array;

	private $assigned_user_id;
	private $date_entered;
	private $db;

	/**
	 * Sole constructor
	 */
	public function __construct() {
		$this->db = DBManagerFactory::getInstance();
	}

	/**
	 * Flags a report_cache as deleted
	 * @return bool True on success
	 */
	public function delete() {
        global $dictionary;
	    global $timedate;
		if(!empty($this->id)) {
            $this->db->updateParams(
                'report_cache',
                $dictionary['report_cache']['fields'],
                array(
                    'deleted' => 1,
                    'date_modified' => $timedate->nowDb(),
                ),
                array(
                    'id' => $this->id,
                    'assigned_user_id' => $this->assigned_user_id,
                )
            );

			return true;
		} // if
		return false;
	}

	/**
	 * Saves report_cache
	 * @return bool
	 */
	public function save() {

		global $current_user, $timedate;
        global $dictionary;

        if ($this->new_with_id) {
            $this->db->insertParams(
                'report_cache',
                $dictionary['report_cache']['fields'],
                array(
                    'id' => $this->id,
                    'assigned_user_id' => $current_user->id,
                    'contents' => $this->contents,
                    'date_entered' => $timedate->nowDb(),
                    'date_modified' => $timedate->nowDb(),
                    'deleted' => 0,
                )
            );
        } else {
            $this->db->updateParams(
                'report_cache',
                $dictionary['report_cache']['fields'],
                array(
                    'contents' => $this->contents,
                    'date_modified' => $timedate->nowDb(),
                ),
                array(
                    'id' => $this->id,
                    'assigned_user_id' => $this->assigned_user_id,
                )
            );
        }

		return true;
	}

	/**
	 * This updates the date_modified value only. This is a special update function
	 *
	 * @return bool
	 */
    public function update()
    {
        global $dictionary;
        global $timedate;

        $this->db->updateParams(
            'report_cache',
            $dictionary['report_cache']['fields'],
            array(
                'date_modified' => $timedate->nowDb(),
            ),
            array(
                'id' => $this->id,
                'assigned_user_id' => $this->assigned_user_id,
            )
        );

		return true;
	} // fn

	/**
	 * This updates the report_options value only. This is a special update function
	 *
	 * @return bool
	 */

	public function updateReportOptions($reportOptions) {
		global $global_json, $current_user, $timedate;
        global $dictionary;
		if (empty($this->report_options_array)) {
			$this->report_options_array = array();
		}
		foreach($reportOptions as $key => $value) {
			$this->report_options_array[$key] = $value;
		} // foreach

		$reportOptionsEncodedData = $global_json->encode($this->report_options_array);
		if($this->new_with_id == true) {
            $this->db->insertParams(
                'report_cache',
                $dictionary['report_cache']['fields'],
                array(
                    'id' => $this->id,
                    'assigned_user_id' => $current_user->id,
                    'report_options' => $reportOptionsEncodedData,
                    'date_entered' => $timedate->nowDb(),
                    'date_modified' => $timedate->nowDb(),
                    'deleted' => 0,
                )
            );
        } else {
            $this->db->updateParams(
                'report_cache',
                $dictionary['report_cache']['fields'],
                array(
                    'report_options' => $reportOptionsEncodedData,
                ),
                array(
                    'id' => $this->id,
                    'assigned_user_id' => $this->assigned_user_id,
                )
            );
        }

		return true;
	} // fn

	/**
	 * Retrieves and populates object
	 * @param string $reportId ID of report
	 * @param string $assigned_user_id ID of user
	 * @return bool True on success
	 */
	public function retrieve($reportId, $assigned_user_id='') {

        global $timedate, $current_user;
		if (empty($assigned_user_id)) {
			$assigned_user_id = $current_user->id;
		} // if
        $q = 'SELECT * FROM report_cache WHERE id = ' . $this->db->quoted($reportId)
            . ' AND assigned_user_id = ' . $this->db->quoted($assigned_user_id)
            . ' AND deleted = 0';
		$r = $this->db->query($q);
		$a = $this->db->fetchByAssoc($r);

		if(!empty($a)) {
			foreach($a as $k => $v) {
				$this->$k = $v;
			}
			$this->date_entered	= $timedate->to_display_date_time($this->date_entered);
			$this->date_modified = $timedate->to_display_date_time($this->date_modified);
            $this->contents_array = json_decode(from_html($this->contents), true);
            $this->report_options_array = json_decode(from_html($this->report_options), true);
			return true;
		}

		return false;
	}
}
