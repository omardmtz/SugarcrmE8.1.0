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



class ReportSchedule{

var $table_name='report_schedules';
    /** @var DBManager */
var $db;

    public function __construct()
    {
        $this->db = DBManagerFactory::getInstance();
    }

/**
 * @deprecated deprecated since this syntax is only supported by MySQL
 * @return void
 * TODO THIS FUNCTIONALITY SHOULD BE REMOVED AFTER CONVERSION TO SUGARBEAN USE
 */
function drop_tables()
{
    // TODO This code should never be used
    $query = 'DROP TABLE IF EXISTS '.$this->table_name;
    $this->db->query($query);
}

function save_schedule($id, $user_id, $report_id, $date_start, $interval, $active, $schedule_type){
	global $timedate;
	$origDateStart = $date_start;
    $date_modified = $timedate->nowDb();
    if (strlen(trim($origDateStart)) == 0) {
        $date_start_str = 'NULL';
    } else {
        $date_start_str = $this->db->quoted($origDateStart);
    }

	if(empty($id))
	{
		$id = create_guid();

		if( empty($date_start) )
		    $date_start = $timedate->nowDb();

        $next_run_date = $this->getNextRunDate($date_start, 0);

        $query = <<<QUERY
INSERT INTO {$this->table_name} (
    id, user_id, report_id, date_start, next_run, time_interval, active, date_modified, schedule_type
)
VALUES (
    {$this->db->quoted($id)},
    {$this->db->quoted($user_id)},
    {$this->db->quoted($report_id)},
    $date_start_str,
    {$this->db->quoted($next_run_date)},
    {$this->db->quoted($interval)},
    {$this->db->quoted($active)},
    {$this->db->quoted($date_modified)},
    {$this->db->quoted($schedule_type)}
)
QUERY;
	}
	else
	{
        $query = <<<QUERY
UPDATE
    $this->table_name
SET
    time_interval = {$this->db->quoted($interval)},
    date_start = $date_start_str,
    active = {$this->db->quoted($active)},
    date_modified = {$this->db->quoted($date_modified)},
    schedule_type = {$this->db->quoted($schedule_type)}
QUERY;
		if(!empty($date_start) && $active)
		{
		    $next_run_date = $this->getNextRunDate($date_start, $interval);
            $query .= ", next_run = " . $this->db->quoted($next_run_date);
		}
        $query .= " WHERE id = " . $this->db->quoted($id);
	}
	$this->db->query($query, true, "error saving schedule");

	return $id;
}

function getNextRunDate($date_start,$interval)
{
    global $timedate;
	$time = time();

    $date_start = $timedate->fromDb($date_start)->ts;

    if($interval > 0)
    {
        // bug #44555: Start Dates are not Respected for Scheduled Reports
        while($date_start <= $time)
        {
            $date_start += $interval;
        }
    }

    return $timedate->fromTimestamp($date_start)->asDb();
}

/**
 * Converts datetime values from the database type
 * NOTE that this is currently hardcoded as this whole module should
 * be converted to using SugarBeans, which would make this obsolete
 * @param $row
 * @return converted row
 * TODO XXX Move this whole module to use SugarBeans
 */
protected function fromConvertReportScheduleDBRow($row){
    if(!$row) return false;
    foreach($row as $name => $value){
        switch($name){
            case 'date_start':
            case 'next_run':
            case 'date_modified':
                $row[$name] = $this->db->fromConvert($row[$name], 'datetime');
            default: break;
        }
    }
    return $row;
}

function get_users_schedule($id=''){
		if(empty($id)){
			global $current_user;
			$id = $current_user->id;
		}
		$return_array = array();
		$query = "SELECT * FROM $this->table_name WHERE user_id='$id'";
		$results = $this->db->query($query);
		while($row = $this->db->fetchByAssoc($results)){
			$return_array[$row['report_id']] = $this->fromConvertReportScheduleDBRow($row);
		}
		return $return_array;
}

function get_report_schedule_for_user($report_id, $user_id=''){
	if(empty($user_id)){
			global $current_user;
			$user_id = $current_user->id;
		}
        $query = sprintf(
            'SELECT * FROM %s WHERE report_id = %s AND user_id = %s AND deleted = 0',
            $this->table_name,
            $this->db->quoted($report_id),
            $this->db->quoted($user_id)
        );
	$results = $this->db->query($query);
	$row = $this->db->fetchByAssoc($results);
	return $this->fromConvertReportScheduleDBRow($row);
}

function get_report_schedule($report_id){
        $query = sprintf(
            'SELECT * FROM %s WHERE report_id = %s AND deleted = 0',
            $this->table_name,
            $this->db->quoted($report_id)
        );
	$results = $this->db->query($query);
	$return_array = array();
	while($row = $this->db->fetchByAssoc($results)){
		$return_array[] = $this->fromConvertReportScheduleDBRow($row);
	}
	return $return_array;
}

    /**
     * Handles failed reports by deactivating them and sending email notifications to owner and subscribed user
     */
    public function handleFailedReports()
    {
        /** @var LoggerManager */
        global $log;

        $schedules_to_deactivate = $this->getSchedulesToDeactivate();
        foreach ($schedules_to_deactivate as $schedule) {
            $log->info('Deactivating report schedule ' . $schedule['id']);
            $this->deactivate($schedule['id']);

            $owner = BeanFactory::retrieveBean('Users', $schedule['owner_id']);
            $subscriber = BeanFactory::retrieveBean('Users', $schedule['subscriber_id']);

            $utils = new ReportsUtilities();
            $utils->sendNotificationOfDisabledReport($schedule['report_id'], $owner, $subscriber);
        }
    }

    /**
     * Finds scheduled reports to be deactivated due to previous failure
     *
     * @return array
     */
    protected function getSchedulesToDeactivate()
    {
        $failure = SchedulersJob::JOB_FAILURE;

        $query = <<<QUERY
SELECT
    rs.id,
    rs.report_id,
    r.assigned_user_id owner_id,
    rs.user_id subscriber_id
FROM
    $this->table_name rs
    INNER JOIN (
        SELECT jq.job_group report_id, jq.execute_time
        FROM job_queue jq
        INNER JOIN (
            SELECT
                max(execute_time) mt,
                job_group
            FROM job_queue
            WHERE target = 'class::SugarJobSendScheduledReport'
            GROUP BY job_group
        ) last
        ON last.mt = jq.execute_time AND last.job_group = jq.job_group
        WHERE resolution = '{$failure}'
    ) j
    ON j.report_id = rs.report_id AND j.execute_time > rs.date_modified
        INNER JOIN saved_reports r
        ON r.id = rs.report_id
            INNER JOIN users u
            ON u.id = rs.user_id
WHERE
    r.deleted = 0
        AND rs.deleted = 0
        AND rs.active = 1
        AND u.status = 'Active'
        AND u.deleted = 0
QUERY;

        $reports = array();
        $result = $this->db->query($query);
        while ($row = $this->db->fetchByAssoc($result)) {
            $reports[] = $row;
        }

        return $reports;
    }

function get_reports_to_email($user_id= '', $schedule_type="pro"){
    global $timedate;
	$where = '';
	if(!empty($user_id)){
		$where = "AND user_id='$user_id'";
	}
	$time = $timedate->nowDb();
	$query = "SELECT report_schedules.* FROM $this->table_name \n".
			"join saved_reports on saved_reports.id=$this->table_name.report_id \n".
			"join users on users.id = report_schedules.user_id".
			" WHERE saved_reports.deleted=0 AND \n" .
			"$this->table_name.next_run < '$time' $where AND \n".
			"$this->table_name.deleted=0 AND \n".
			"$this->table_name.active=1 AND " .
			"$this->table_name.schedule_type='".$schedule_type."' AND\n".
			"users.status='Active' AND users.deleted='0'".
			"ORDER BY $this->table_name.next_run ASC";

	$results = $this->db->query($query);
	$return_array = array();
	while ($row = $this->db->fetchByAssoc($results))
    {
        $return_array[] = $this->fromConvertReportScheduleDBRow($row);
	}
	return $return_array;

}

function get_ent_reports_to_email($user_id= '', $schedule_type="ent"){
	$where = '';
	if(!empty($user_id)){
		$where = "AND user_id='$user_id'";
	}
	$time = gmdate($GLOBALS['timedate']->get_db_date_time_format(), time());
	$query = "SELECT report_schedules.* FROM $this->table_name \n".
			"join report_maker on report_maker.id=$this->table_name.report_id \n".
			"join users on users.id = report_schedules.user_id".
			" WHERE report_maker.deleted=0 AND \n" .
			"$this->table_name.next_run < '$time' $where AND \n".
			"$this->table_name.deleted=0 AND \n".
			"$this->table_name.active=1 AND " .
			"$this->table_name.schedule_type='".$schedule_type."' AND\n".
			"users.status='Active' AND users.deleted='0'".
			"ORDER BY $this->table_name.next_run ASC";
	$results = $this->db->query($query);
	$return_array = array();
	while($row = $this->db->fetchByAssoc($results)){
			$return_array[$row['report_id']] = $this->fromConvertReportScheduleDBRow($row);
	}
	return $return_array;

}

function update_next_run_time($schedule_id, $next_run, $interval){
        global $timedate;
		$last_run = $timedate->fromDb($next_run)->ts;
		$time = time();
		while($last_run <= $time){
			$last_run += $interval;
		}
		$next_run = $timedate->fromTimestamp($last_run)->asDb();
		$query = "UPDATE $this->table_name SET next_run='$next_run' WHERE id='$schedule_id'";
		$this->db->query($query);

}

    /**
     * Deactivates the given schedule
     *
     * @param string $id Schedule ID
     */
    public function deactivate($id)
    {
        $query = "UPDATE $this->table_name SET active = 0 WHERE id = " . $this->db->quoted($id);
        $this->db->query($query);
    }

function mark_deleted($id){
        $query = "UPDATE {$this->table_name} SET deleted = '1' WHERE id = ?";
        $conn = $this->db->getConnection();
        $conn->executeQuery($query, array($id));
}

    /**
     * Checks if Scheduler "Run Report Generation Scheduled Tasks"
     * is active
     *
     * @return boolean true if the scheduler is active, false otherwise
     */
    public function isReportSchedulerActive()
    {
        // Look for the Scheduler by 'job', since name is localized
        $fields = array(
            'job' => 'function::processQueue',
            'status' => 'Active',
        );

        $scheduler = new Scheduler();
        $scheduler = $scheduler->retrieve_by_string_fields($fields);

        return !empty($scheduler);
    }

    /**
     * Returns report schedule properties
     *
     * @param string $id Report schedule ID
     *
     * @return array
     */
    public function getInfo($id)
    {
        $query = "SELECT report_id, next_run, time_interval
        FROM {$this->table_name}
        WHERE id = " . $this->db->quoted($id);
        $result = $this->db->query($query);
        $row = $this->db->fetchByAssoc($result);
        $row = $this->fromConvertReportScheduleDBRow($row);

        return $row;
    }
}
