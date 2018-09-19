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

$mod_strings = array (

    // Dashboard Names
    'LBL_FORECASTS_DASHBOARD' => 'แดชบอร์ดการคาดการณ์',

    //module strings.
    'LBL_MODULE_NAME' => 'ประมาณการ',
    'LBL_MODULE_NAME_SINGULAR' => 'ประมาณการ',
    'LNK_NEW_OPPORTUNITY' => 'สร้างโอกาสทางการขาย',
    'LBL_MODULE_TITLE' => 'ประมาณการ',
    'LBL_LIST_FORM_TITLE' => 'ประมาณการที่คอมมิต',
    'LNK_UPD_FORECAST' => 'เวิร์กชีทประมาณการ',
    'LNK_QUOTA' => 'ดูโควตา',
    'LNK_FORECAST_LIST' => 'ดูประวัติของประมาณการ',
    'LBL_FORECAST_HISTORY' => 'ประมาณการ: ประวัติ',
    'LBL_FORECAST_HISTORY_TITLE' => 'ประวัติ',

    //var defs
    'LBL_TIMEPERIOD_NAME' => 'ช่วงเวลา',
    'LBL_USER_NAME' => 'ชื่อผู้ใช้',
    'LBL_REPORTS_TO_USER_NAME' => 'ผู้บังคับบัญชา',

    //forecast table
    'LBL_FORECAST_ID' => 'ID ประมาณการ',
    'LBL_FORECAST_TIME_ID' => 'ID ช่วงเวลา',
    'LBL_FORECAST_TYPE' => 'ประเภทประมาณการ',
    'LBL_FORECAST_OPP_COUNT' => 'จำนวนโอกาสทางการขายทั้งหมด',
    'LBL_FORECAST_PIPELINE_OPP_COUNT' => 'จำนวนโอกาสทางการขายของกระบวนการขาย',
    'LBL_FORECAST_OPP_WEIGH'=> 'จำนวนเงินถ่วงน้ำหนัก',
    'LBL_FORECAST_OPP_COMMIT' => 'เคสที่เป็นไปได้',
    'LBL_FORECAST_OPP_BEST_CASE'=>'เคสที่ดีที่สุด',
    'LBL_FORECAST_OPP_WORST'=>'เคสที่แย่ที่สุด',
    'LBL_FORECAST_USER' => 'ผู้ใช้',
    'LBL_DATE_COMMITTED'=> 'วันที่คอมมิต',
    'LBL_DATE_ENTERED' => 'วันที่ป้อน',
    'LBL_DATE_MODIFIED' => 'วันที่แก้ไข',
    'LBL_CREATED_BY' => 'สร้างโดย',
    'LBL_DELETED' => 'ลบ',
    'LBL_MODIFIED_USER_ID'=>'แก้ไขโดย',
    'LBL_WK_VERSION' => 'เวอร์ชัน',
    'LBL_WK_REVISION' => 'รุ่น',

    //Quick Commit labels.
    'LBL_QC_TIME_PERIOD' => 'ช่วงเวลา:',
    'LBL_QC_OPPORTUNITY_COUNT' => 'จำนวนโอกาสทางการขาย:',
    'LBL_QC_WEIGHT_VALUE' => 'จำนวนเงินถ่วงน้ำหนัก:',
    'LBL_QC_COMMIT_VALUE' => 'จำนวนเงินที่คอมมิต:',
    'LBL_QC_COMMIT_BUTTON' => 'คอมมิต',
    'LBL_QC_WORKSHEET_BUTTON' => 'เวิร์กชีท',
    'LBL_QC_ROLL_COMMIT_VALUE' => 'จำนวนเงินที่คอมมิตที่ทบยอด:',
    'LBL_QC_DIRECT_FORECAST' => 'ประมาณการทางตรงของฉัน:',
    'LBL_QC_ROLLUP_FORECAST' => 'ประมาณการกลุ่มของฉัน:',
    'LBL_QC_UPCOMING_FORECASTS' => 'ประมาณการของฉัน',
    'LBL_QC_LAST_DATE_COMMITTED' => 'วันที่คอมมิตครั้งล่าสุด:',
    'LBL_QC_LAST_COMMIT_VALUE' => 'จำนวนเงินที่คอมมิตล่าสุด:',
    'LBL_QC_HEADER_DELIM'=> 'ถึง',

    //opportunity worksheet list view labels
    'LBL_OW_OPPORTUNITIES' => "โอกาสทางการขาย",
    'LBL_OW_ACCOUNTNAME' => "บัญชี",
    'LBL_OW_REVENUE' => "จำนวนเงิน",
    'LBL_OW_WEIGHTED' => "จำนวนเงินถ่วงน้ำหนัก",
    'LBL_OW_MODULE_TITLE'=> 'เวิร์กชีทโอกาสทางการขาย',
    'LBL_OW_PROBABILITY'=>'ความน่าจะเป็น',
    'LBL_OW_NEXT_STEP'=>'ขั้นตอนถัดไป',
    'LBL_OW_DESCRIPTION'=>'คำอธิบาย',
    'LBL_OW_TYPE'=>'ประเภท',

    //forecast worksheet direct reports forecast
    'LBL_FDR_USER_NAME'=>'ผู้ใต้บังคับบัญชา',
    'LBL_FDR_OPPORTUNITIES'=>'โอกาสทางการขายในประมาณการ:',
    'LBL_FDR_WEIGH'=>'จำนวนเงินถ่วงน้ำหนักของโอกาสทางการขาย:',
    'LBL_FDR_COMMIT'=>'จำนวนเงินที่คอมมิต',
    'LBL_FDR_DATE_COMMIT'=>'วันที่คอมมิต',

    //detail view.
    'LBL_DV_HEADER' => 'ประมาณการ: เวิร์กชีท',
    'LBL_DV_MY_FORECASTS' => 'ประมาณการของฉัน',
    'LBL_DV_MY_TEAM' => "ประมาณการของทีม" ,
    'LBL_DV_TIMEPERIODS' => 'ช่วงเวลา:',
    'LBL_DV_FORECAST_PERIOD' => 'ช่วงเวลาของประมาณการ',
    'LBL_DV_FORECAST_OPPORTUNITY' => 'โอกาสทางการขายของประมาณการ',
    'LBL_SEARCH' => 'เลือก',
    'LBL_SEARCH_LABEL' => 'เลือก',
    'LBL_COMMIT_HEADER' => 'การคอมมิตประมาณการ',
    'LBL_DV_LAST_COMMIT_DATE' =>'วันที่คอมมิตครั้งล่าสุด:',
    'LBL_DV_LAST_COMMIT_AMOUNT' =>'จำนวนเงินที่คอมมิตล่าสุด:',
    'LBL_DV_FORECAST_ROLLUP' => 'การทบยอดประมาณการ',
    'LBL_DV_TIMEPERIOD' => 'ช่วงเวลา:',
    'LBL_DV_TIMPERIOD_DATES' => 'ช่วงวันที่:',
    'LBL_LOADING_COMMIT_HISTORY' => 'กำลังโหลดประวัติการคอมมิต...',

    //list view
    'LBL_LV_TIMPERIOD'=> 'ช่วงเวลา',
    'LBL_LV_TIMPERIOD_START_DATE'=> 'วันที่เริ่มต้น',
    'LBL_LV_TIMPERIOD_END_DATE'=> 'วันที่สิ้นสุด',
    'LBL_LV_TYPE'=> 'ประเภทประมาณการ',
    'LBL_LV_COMMIT_DATE'=> 'วันที่คอมมิต',
    'LBL_LV_OPPORTUNITIES'=> 'โอกาสทางการขาย',
    'LBL_LV_WEIGH'=> 'จำนวนเงินถ่วงน้ำหนัก',
    'LBL_LV_COMMIT'=> 'จำนวนเงินที่คอมมิต',

    'LBL_COMMIT_NOTE' => 'ป้อนจำนวนเงินที่คุณต้องการคอมมิตสำหรับช่วงเวลาที่เลือกไว้:',
    'LBL_COMMIT_TOOLTIP' => 'ในการเปิดใช้งานการคอมมิต: แก้ไขค่าในเวิร์กชีท',
    'LBL_COMMIT_MESSAGE' => 'คุณต้องการคอมมิตจำนวนเงินเหล่านี้หรือไม่',
    'ERR_FORECAST_AMOUNT' => 'ต้องระบุจำนวนเงินที่คอมมิต และค่าที่ระบุจะต้องเป็นตัวเลข',

    // js error strings
    'LBL_FC_START_DATE' => 'วันที่เริ่มต้น',
    'LBL_FC_USER' => 'กำหนดการสำหรับ',

    'LBL_NO_ACTIVE_TIMEPERIOD'=>'ไม่มีช่วงเวลาที่มีสถานะใช้งานสำหรับโมดูลประมาณการ',
    'LBL_FDR_ADJ_AMOUNT'=>'จำนวนเงินที่ปรับปรุง',
    'LBL_SAVE_WOKSHEET'=>'บันทึกเวิร์กชีท',
    'LBL_RESET_WOKSHEET'=>'รีเซ็ตเวิร์กชีท',
    'LBL_SHOW_CHART'=>'ดูแผนภูมิ',
    'LBL_RESET_CHECK'=>'ข้อมูลของเวิร์กชีททั้งหมดในช่วงเวลาที่เลือกไว้และผู้ใช้ที่ล็อกอินจะถูกลบออก ต้องการดำเนินการต่อหรือไม่',

    'LB_FS_LIKELY_CASE'=>'เคสที่เป็นไปได้',
    'LB_FS_WORST_CASE'=>'เคสที่แย่ที่สุด',
    'LB_FS_BEST_CASE'=>'เคสที่ดีที่สุด',
    'LBL_FDR_WK_LIKELY_CASE'=>'เคสที่เป็นไปได้โดยประมาณ',
    'LBL_FDR_WK_BEST_CASE'=> 'เคสที่ดีที่สุดโดยประมาณ',
    'LBL_FDR_WK_WORST_CASE'=>'เคสที่แย่ที่สุดโดยประมาณ',
    'LBL_FDR_C_BEST_CASE'=>'เคสที่ดีที่สุด',
    'LBL_FDR_C_WORST_CASE'=>'เคสที่แย่ที่สุด',
    'LBL_FDR_C_LIKELY_CASE'=>'เคสที่เป็นไปได้',
    'LBL_QC_LAST_BEST_CASE'=>'จำนวนเงินที่ตกลงล่าสุด (เคสที่ดีที่สุด):',
    'LBL_QC_LAST_LIKELY_CASE'=>'จำนวนเงินที่ตกลงล่าสุด (เคสที่เป็นไปได้):',
    'LBL_QC_LAST_WORST_CASE'=>'จำนวนเงินที่ตกลงล่าสุด (เคสที่แย่ที่สุด):',
    'LBL_QC_ROLL_BEST_VALUE'=>'จำนวนเงินที่ตกลงที่ทบยอด (เคสที่ดีที่สุด):',
    'LBL_QC_ROLL_LIKELY_VALUE'=>'จำนวนเงินที่ตกลงที่ทบยอด (เคสที่เป็นไปได้):',
    'LBL_QC_ROLL_WORST_VALUE'=>'จำนวนเงินที่ตกลงที่ทบยอด (เคสที่แย่ที่สุด):',
    'LBL_QC_COMMIT_BEST_CASE'=>'จำนวนเงินที่ตกลง (เคสที่ดีที่สุด):',
    'LBL_QC_COMMIT_LIKELY_CASE'=>'จำนวนเงินที่ตกลง (เคสที่เป็นไปได้):',
    'LBL_QC_COMMIT_WORST_CASE'=>'จำนวนเงินที่ตกลง (เคสที่แย่ที่สุด):',
    'LBL_CURRENCY' => 'สกุลเงิน',
    'LBL_CURRENCY_ID' => 'ID สกุลเงิน',
    'LBL_CURRENCY_RATE' => 'อัตราสกุลเงิน',
    'LBL_BASE_RATE' => 'อัตราฐาน',

    'LBL_QUOTA' => 'โควตา',
    'LBL_QUOTA_ADJUSTED' => 'โควตา (ปรับค่า)',

    'LBL_FORECAST_FOR'=>'เวิร์กชีทประมาณการสำหรับ: ',
    'LBL_FMT_ROLLUP_FORECAST'=>'(ทบยอด)',
    'LBL_FMT_DIRECT_FORECAST'=>'(โดยตรง)',

    //labels used by the chart.
    'LBL_GRAPH_TITLE'=>'ประวัติของประมาณการ',
    'LBL_GRAPH_QUOTA_ALTTEXT'=>'โควตาสำหรับ %s',
    'LBL_GRAPH_COMMIT_ALTTEXT'=>'จำนวนเงินที่คอมมิตสำหรับ %s',
    'LBL_GRAPH_OPPS_ALTTEXT'=>'มูลค่าของโอกาสทางการขายที่ปิดได้ใน %s',

    'LBL_GRAPH_QUOTA_LEGEND'=>'โควตา',
    'LBL_GRAPH_COMMIT_LEGEND'=>'ประมาณการที่คอมมิต',
    'LBL_GRAPH_OPPS_LEGEND'=>'โอกาสทางการขายที่ปิด',
    'LBL_TP_QUOTA'=>'โควตา:',
    'LBL_CHART_FOOTER'=>'ประวัติของประมาณการ <br>จำนวนเงินโควตากับประมาณการ เทียบกับมูลค่าของโอกาสทางการขายที่ปิด',
    'LBL_TOTAL_VALUE'=>'รวม:',
    'LBL_COPY_AMOUNT'=>'ยอดเงินรวม',
    'LBL_COPY_WEIGH_AMOUNT'=>'จำนวนเงินถ่วงน้ำหนักรวม',
    'LBL_WORKSHEET_AMOUNT'=>'ยอดเงินรวมโดยประมาณ',
    'LBL_COPY'=>'คัดลอกค่า',
    'LBL_COMMIT_AMOUNT'=>'มูลค่าที่คอมมิตรวม',
    'LBL_CUMULATIVE_TOTAL'=>'ผลรวมสะสม',
    'LBL_COPY_FROM'=>'คัดลอกค่าจาก:',

    'LBL_CHART_TITLE'=>'โควตาเทียบกับคอมมิตเทียบกับมูลค่าตามจริง',

    'LBL_FORECAST' => 'ประมาณการ',
    'LBL_COMMIT_STAGE' => 'ขั้นตอนที่คอมมิต',
    'LBL_SALES_STAGE' => 'ขั้นตอน',
    'LBL_AMOUNT' => 'จำนวนเงิน',
    'LBL_PERCENT' => 'เปอร์เซ็นต์',
    'LBL_DATE_CLOSED' => 'การปิดที่คาดไว้',
    'LBL_PRODUCT_ID' => 'ID ผลิตภัณฑ์',
    'LBL_QUOTA_ID' => 'ID โควตา',
    'LBL_VERSION' => 'เวอร์ชัน',
    'LBL_CHART_BAR_LEGEND_CLOSE' => 'ซ่อนแถบคำอธิบาย',
    'LBL_CHART_BAR_LEGEND_OPEN' => 'แสดงแถบคำอธิบาย',
    'LBL_CHART_LINE_LEGEND_CLOSE' => 'ซ่อนแนวคำอธิบาย',
    'LBL_CHART_LINE_LEGEND_OPEN' => 'แสดงแนวคำอธิบาย',

    //Labels for forecasting history log and endpoint
    'LBL_ERROR_NOT_MANAGER' => 'ข้อผิดพลาด: ผู้ใช้ {0} ไม่มีสิทธิ์เข้าถึงของผู้จัดการในการส่งคำขอประมาณการสำหรับ {1}',
    'LBL_UP' => 'ขึ้น',
    'LBL_DOWN' => 'ลง',
    'LBL_PREVIOUS_COMMIT' => 'คอมมิตครั้งล่าสุด:',

    'LBL_COMMITTED_HISTORY_SETUP_FORECAST' => 'ตั้งค่าประมาณการ',
    'LBL_COMMITTED_HISTORY_UPDATED_FORECAST' => 'ประมาณการที่อัปเดต',
    'LBL_COMMITTED_HISTORY_1_SHOWN' => '{{{intro}}} {{{first}}}',
    'LBL_COMMITTED_HISTORY_2_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}}',
    'LBL_COMMITTED_HISTORY_3_SHOWN' => '{{{intro}}} {{{first}}}, {{{second}}} และ {{{third}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_CHANGED' => 'เป็นไปได้ {{{direction}}} {{{from}}} เป็น {{{to}}}',
    'LBL_COMMITTED_HISTORY_BEST_CHANGED' => 'ดีที่สุด {{{direction}}} {{{from}}} เป็น {{{to}}}',
    'LBL_COMMITTED_HISTORY_WORST_CHANGED' => 'แย่ที่สุด {{{direction}}} {{{from}}} เป็น {{{to}}}',
    'LBL_COMMITTED_HISTORY_LIKELY_SAME' => 'เป็นไปได้มีค่าคงเดิม',
    'LBL_COMMITTED_HISTORY_BEST_SAME' => 'ดีที่สุดมีค่าคงเดิม',
    'LBL_COMMITTED_HISTORY_WORST_SAME' => 'แย่ที่สุดมีค่าคงเดิม',


    'LBL_COMMITTED_THIS_MONTH' => 'เดือนนี้ใน {0}',
    'LBL_COMMITTED_MONTHS_AGO' => '{0} เดือนก่อนใน {1}',

    //Labels for jsTree implementation
    'LBL_TREE_PARENT' => 'หลัก',

    // Label for Current User Rep Worksheet Line
    // &#x200E; tells the browser to interpret as left-to-right
    'LBL_MY_MANAGER_LINE' => '{0} (me)&#x200E;',

    //Labels for worksheet items
    'LBL_EXPECTED_OPPORTUNITIES' => 'โอกาสทางการขายที่คาดไว้',
    'LBL_DISPLAYED_TOTAL' => 'ผลรวมที่แสดง',
    'LBL_TOTAL' => 'รวม',
    'LBL_OVERALL_TOTAL' => 'ผลรวมทั้งหมด',
    'LBL_EDITABLE_INVALID' => 'ค่าไม่ถูกต้องสำหรับ {0}',
    'LBL_EDITABLE_INVALID_RANGE' => 'ค่าต้องอยู่ระหว่าง {0} และ {1}',
    'LBL_WORKSHEET_SAVE_CONFIRM_UNLOAD' => 'คุณมีการเปลี่ยนแปลงที่ยังไม่ได้บันทึกในเวิร์กชีท',
    'LBL_WORKSHEET_EXPORT_CONFIRM' => 'ระบบจะส่งออกเฉพาะข้อมูลที่บันทึกหรือคอมมิต ยกเลิกเพื่อล้มเลิกขั้นตอนนี้ ยืนยันเพื่อส่งออกข้อมูลที่บันทึกไว้',
    'LBL_WORKSHEET_ID' => 'ID เวิร์กชีท',

    // Labels for Chart Options
    'LBL_DATA_SET' => 'ชุดข้อมูล:',
    'LBL_GROUP_BY' => 'จัดกลุ่มตาม:',
    'LBL_CHART_OPTIONS' => 'ตัวเลือกของแผนภูมิ',
    'LBL_CHART_AMOUNT' => 'จำนวนเงิน',
    'LBL_CHART_TYPE' => 'ประเภท',

    // Labels for Data Filters
    'LBL_FILTERS' => 'ตัวกรอง',

    // Labels for toggle buttons
    'LBL_MORE' => 'เพิ่มเติม',
    'LBL_LESS' => 'น้อยลง',

    // Labels for Progress
    'LBL_PROJECTED' => 'คาดการณ์',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_QUOTA' => 'เป็นไปได้มากกว่าโควตา',
    'LBL_DISTANCE_LEFT_LIKELY_TO_QUOTA' => 'เป็นไปได้ต่ำกว่าโควตา',
    'LBL_DISTANCE_ABOVE_BEST_FROM_QUOTA' => 'ดีที่สุดมากกว่าโควตา',
    'LBL_DISTANCE_LEFT_BEST_TO_QUOTA' => 'ดีที่สุดต่ำกว่าโควตา',
    'LBL_DISTANCE_ABOVE_WORST_FROM_QUOTA' => 'แย่ที่สุดมากกว่าโควตา',
    'LBL_DISTANCE_LEFT_WORST_TO_QUOTA' => 'แย่ที่สุดต่ำกว่าโควตา',
    'LBL_CLOSED' => 'ปิดโดยได้รับการขาย',
    'LBL_DISTANCE_ABOVE_LIKELY_FROM_CLOSED' => 'เป็นไปได้มากกว่าที่ปิด',
    'LBL_DISTANCE_LEFT_LIKELY_TO_CLOSED' => 'เป็นไปได้ต่ำกว่าที่ปิด',
    'LBL_DISTANCE_ABOVE_BEST_FROM_CLOSED' => 'ดีที่สุดมากกว่าที่ปิด',
    'LBL_DISTANCE_LEFT_BEST_TO_CLOSED' => 'ดีที่สุดต่ำกว่าที่ปิด',
    'LBL_DISTANCE_ABOVE_WORST_FROM_CLOSED' => 'แย่ที่สุดมากกว่าที่ปิด',
    'LBL_DISTANCE_LEFT_WORST_TO_CLOSED' => 'แย่ที่สุดต่ำกว่าที่ปิด',
    'LBL_REVENUE' => 'รายได้',
    'LBL_PIPELINE_REVENUE' => 'รายได้ของกระบวนการขาย',
    'LBL_PIPELINE_OPPORTUNITIES' => 'โอกาสทางการขายของกระบวนการขาย',
    'LBL_LOADING' => 'กำลังโหลด',
    'LBL_IN_FORECAST' => 'การประมาณการ',

    // Actions Dropdown
    'LBL_ACTIONS' => 'การดำเนินการ',
    'LBL_EXPORT_CSV' => 'ส่งออก CSV',
    'LBL_CANCEL' => 'ยกเลิก',

    'LBL_CHART_FORECAST_FOR' => ' สำหรับ {0}',
    'LBL_FORECAST_TITLE' => 'ประมาณการ: {0}',
    'LBL_CHART_INCLUDED' => 'รวม',
    'LBL_CHART_NOT_INCLUDED' => 'ไม่รวม',
    'LBL_CHART_ADJUSTED' => ' (ปรับค่า)',
    'LBL_SAVE_DRAFT' => 'บันทึกแบบร่าง',
    'LBL_CHANGES_BY' => 'การเปลี่ยนแปลงโดย {0}',
    'LBL_FORECAST_SETTINGS' => 'การตั้งค่า',

    // config panels strings
    'LBL_FORECASTS_CONFIG_TITLE' => 'การตั้งค่าประมาณการ',

    'LBL_FORECASTS_MISSING_STAGE_TITLE' => 'ข้อผิดพลาดในการกำหนดค่าประมาณการ:',
    'LBL_FORECASTS_MISSING_SALES_STAGE_VALUES' => 'โมดูลประมาณการมีการกำหนดค่าไม่ถูกต้องและไม่สามารถใช้งานได้อีก ขั้นตอนการขายที่ได้รับการขายและขั้นตอนการขายที่ไม่ได้รับการขายขาดหายไปจากค่าขั้นตอนการขายที่ใช้ได้ โปรดติดต่อผู้ดูแลระบบของคุณ',
    'LBL_FORECASTS_ACLS_NO_ACCESS_TITLE' => 'ข้อผิดพลาดในการเข้าถึงประมาณการ:',
    'LBL_FORECASTS_ACLS_NO_ACCESS_MSG' => 'คุณไม่มีสิทธิ์เข้าถึงโมดูลประมาณการ โปรดติดต่อผู้ดูแลระบบ',

    'LBL_FORECASTS_RECORDS_ACLS_NO_ACCESS_MSG' => 'คุณไม่มีสิทธิ์เข้าถึงระเบียนของโมดูลประมาณการ โปรดติดต่อผู้ดูแลระบบ',

    // Panel and BreadCrumb Labels
    'LBL_FORECASTS_CONFIG_BREADCRUMB_WORKSHEET_LAYOUT' => 'เลย์เอาต์ของเวิร์กชีท',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_RANGES' => 'ช่วง',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_SCENARIOS' => 'สถานการณ์',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_TIMEPERIODS' => 'ช่วงเวลา',
    'LBL_FORECASTS_CONFIG_BREADCRUMB_VARIABLES' => 'ตัวแปร',

    // Admin UI
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_SETTINGS' => 'การตั้งค่าประมาณการ',
    'LBL_FORECASTS_CONFIG_TITLE_TIMEPERIODS' => 'ช่วงเวลา',
    'LBL_FORECASTS_CONFIG_TITLE_RANGES' => 'ช่วงของประมาณการ',
    'LBL_FORECASTS_CONFIG_TITLE_SCENARIOS' => 'สถานการณ์',
    'LBL_FORECASTS_CONFIG_TITLE_WORKSHEET_COLUMNS' => 'คอลัมน์ของเวิร์กชีท',
    'LBL_FORECASTS_CONFIG_TITLE_FORECAST_BY' => 'ดูเวิร์กชีทประมาณการตาม',

    'LBL_FORECASTS_CONFIG_HOWTO_TITLE_FORECAST_BY' => 'ประมาณการโดย',

    'LBL_FORECASTS_CONFIG_TITLE_MESSAGE_TIMEPERIODS' => 'วันที่เริ่มต้นของปีการเงิน:',

    'LBL_FORECASTS_CONFIG_HELP_TIMEPERIODS' => 'กำหนดค่าระยะเวลาที่จะใช้ในโมดูลประมาณการ <br><br>โปรดทราบว่าการตั้งค่าระยะเวลาจะไม่สามารถเปลี่ยนแปลงได้หลังจากตั้งค่าครั้งแรก<br><br>เริ่มต้นด้วยการเลือกวันที่เริ่มต้นของปีการเงิน ช่วงวันที่สำหรับช่วงเวลาจะมีการคำนวณโดยอัตโนมัติตามการเลือกของคุณ ช่วงเวลาย่อยจะอ้างอิงเวิร์กชีทประมาณการ <br><br>อนาคตที่สามารถดูได้ และช่วงเวลาที่ผ่านไปจะเป็นตัวกำหนดจำนวนช่วงเวลาย่อยที่ปรากฏในโมดูลประมาณการ ผู้ใช้จะสามารถดูและแก้ไขตัวเลขประมาณการในช่วงเวลาย่อยที่ปรากฏ',
    'LBL_FORECASTS_CONFIG_HELP_RANGES' => 'กำหนดค่าว่าคุณต้องการจัดหมวดหมู่ {{forecastByModule}} อย่างไร <br><br>โปรดทราบว่าการตั้งค่าขอบเขตไม่สามารถเปลี่ยนแปลงได้หลังจากที่ได้มอบหมายครั้งแรก และสำหรับอินสแตนซ์ที่อัปเกรดแล้ว การตั้งค่าขอบเขตจะถูกล็อกไว้ในข้อมูลคาดการณ์ที่มีอยู่ <br><br>คุณสามารถเลือกสองหมวดหมู่หรือมากกว่านั้นได้ ตามขอบเขตความน่าจะเป็น หรือสร้างหมวดหมู่ที่ไม่อยู่ในความน่าจะเป็น <br><br>มีช่องสี่เหลี่ยมสำหรับทำเครื่องหมายอยู่ตรงตำแหน่งด้านซ้ายมือของหมวดหมู่ที่คุณกำหนดเอง ซึ่งคุณสามารถใช้ช่องเหล่านี้เพื่อตัดสินใจว่าภายในจำนวนการคาดการณ์ที่มอบหมายนั้นจะครอบคลุมไปถึงขอบเขตใด และรายงานไปยังผู้จัดการ <br><br>ผู้ใช้สามารถเปลี่ยนสถานะ รวม/ไม่รวม และเปลี่ยนหมวดหมู่ของ {{forecastByModule}} ได้เองจากแผนงานของพวกเขา',
    'LBL_FORECASTS_CONFIG_HELP_SCENARIOS' => 'เลือกคอลัมน์ที่คุณต้องการให้ผู้ใช้กรอกข้อมูลประมาณการของตนสำหรับแต่ละ {{forecastByModuleSingular}} โปรดทราบว่าจำนวนเงินที่เป็นไปได้นั้นจะเชื่อมโยงกับจำนวนเงินที่ปรากฏใน {{forecastByModule}} ดังนั้น คอลัมน์ "เป็นไปได้" จึงไม่สามารถซ่อนได้',
    'LBL_FORECASTS_CONFIG_HELP_WORKSHEET_COLUMNS' => 'เลือกคอลัมน์ที่คุณต้องการดูในโมดูลประมาณการ รายการของฟิลด์จะรวมเวิร์กชีท และอนุญาตให้ผู้ใช้เลือกวิธีกำหนดค่ามุมมองได้',
    'LBL_FORECASTS_CONFIG_HELP_FORECAST_BY' => 'ตำแหน่งนี้เป็นตัวจองพื้นที่สำหรับข้อความอธิบาย "ประมาณการโดย"!',
    'LBL_FORECASTS_CONFIG_SETTINGS_SAVED' => 'บันทึกการกำหนดค่าประมาณการแล้ว',

    // timeperiod config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_SETUP_NOTICE' => 'ไม่สามารถแก้ไขการตั้งค่าช่วงเวลาหลังจากการตั้งค่าเริ่มแรก',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_DESC' => 'กำหนดค่าช่วงเวลาที่ใช้สำหรับโมดูลประมาณการ',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_TYPE' => 'เลือกประเภทของปีที่องค์กรใช้สำหรับการทำบัญชี',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD' => 'เลือกประเภทของช่วงเวลา',
    'LBL_FORECASTS_CONFIG_LEAFPERIOD' => 'เลือกช่วงเวลาย่อยที่ต้องการใช้ดูช่วงเวลาของคุณ:',
    'LBL_FORECASTS_CONFIG_START_DATE' => 'เลือกวันที่เริ่มต้นของปีการเงิน',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_FORWARD' => 'เลือกจำนวนช่วงเวลาในอนาคตที่จะดูในเวิร์กชีท<br><i>ตัวเลขนี้จะมีผลกับช่วงเวลาฐานที่เลือก ตัวอย่างเช่น เมื่อเลือก 2 กับช่วงเวลารายปี ระบบจะแสดงไตรมาสในอนาคต 8 ไตรมาส</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIODS_BACKWARD' => 'เลือกจำนวนช่วงเวลาในอดีตที่จะดูในเวิร์กชีท<br><i>ตัวเลขนี้จะมีผลกับช่วงเวลาฐานที่เลือก ตัวอย่างเช่น เมื่อเลือก 2 กับช่วงเวลารายไตรมาส ระบบจะแสดง 6 เดือนที่ผ่านมา</i>',
    'LBL_FORECASTS_CONFIG_TIMEPERIOD_FISCAL_YEAR' => 'วันที่เริ่มต้นที่เลือกแสดงว่าปีเงินอาจครอบคลุมสองปี โปรดเลือกปีที่จะใช้เป็นปีการเงิน:',
    'LBL_FISCAL_YEAR' => 'ปีการเงิน',

    // worksheet layout config
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_TEXT' => 'เลือกวิธีเติมข้อมูลเวิร์กชีทประมาณการ:',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_OPPORTUNITIES' => 'โอกาสทางการขาย',
    'LBL_FORECASTS_CONFIG_GENERAL_FORECAST_BY_PRODUCT_LINE_ITEMS' => 'รายการบรรทัดรายได้',
    'LBL_REVENUELINEITEM_NAME' => 'ชื่อรายการบรรทัดรายได้',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LAYOUT_DETAIL_MESSAGE' => 'ระบบจะเติมข้อมูลเวิร์กชีทด้วย:',

    // ranges config
    //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
    'LBL_FORECASTS_CONFIG_RANGES_SETUP_NOTICE' => 'การตั้งค่าช่วงจะไม่สามารถเปลี่ยนแปลงได้หลังจากการบันทึกร่างหรือคอมมิตครั้งแรกในโมดูลประมาณการ แต่สำหรับอินสแตนซ์ที่อัปเกรด การตั้งค่าช่วงจะไม่สามารถเปลี่ยนแปลงได้หลังจากการตั้งค่าครั้งแรก เนื่องจากข้อมูลประมาณการจะมีอยู่แล้วจากการอัปเกรด',
    'LBL_FORECASTS_CONFIG_RANGES' => 'ตัวเลือกของช่วงประมาณการ:',
    'LBL_FORECASTS_CONFIG_RANGES_OPTIONS' => 'เลือกว่าจะจัดหมวดหมู่ {{forecastByModule}} อย่างไร',
    'LBL_FORECASTS_CONFIG_SHOW_BINARY_RANGES_DESCRIPTION' => 'ตัวเลือกนี้ช่วยให้ผู้ใช้สามารถระบุ {{forecastByModule}} ที่จะรวมหรือไม่รวมในประมาณการ',
    'LBL_FORECASTS_CONFIG_SHOW_BUCKETS_RANGES_DESCRIPTION' => 'ตัวเลือกนี้จะทำให้ผู้ใช้สามารถจัดหมวดหมู่ {{forecastByModule}} ที่ไม่รวมในการคอมมิต แต่มีความเป็นไปได้ที่จะปิดได้ถ้ามีเงื่อนไขที่ดี และ {{forecastByModule}} ที่จะไม่รวมในประมาณการ',
    'LBL_FORECASTS_CONFIG_SHOW_CUSTOM_BUCKETS_RANGES_DESCRIPTION' => 'ช่วงที่กำหนดเอง: ตัวเลือกนี้ทำให้ผู้ใช้สามารถจัดหมวดหมู่ {{forecastByModule}} ที่จะคอมมิตในประมาณการให้เป็นช่วงที่คอมมิต ช่วงที่ไม่รวม และช่วงอื่นๆ ที่คุณตั้งค่าขึ้น',
    'LBL_FORECASTS_CONFIG_RANGES_EXCLUDE_INFO' => 'ช่วงที่ไม่รวมคือ 0% ถึงค่าต่ำสุดของช่วงประมาณการก่อนหน้านั้นโดยค่าเริ่มต้น',

    'LBL_FORECASTS_CONFIG_RANGES_ENTER_RANGE' => 'ป้อนชื่อของช่วง...',

    // scenarios config
    //TODO-sfa refactors the code references for scenarios to be scenarios (SFA-337).
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS' => 'เลือกสถานการณ์ที่จะรวมไว้ในเวิร์กชีทประมาณการ',
    'LBL_FORECASTS_CONFIG_WORKSHEET_LIKELY_INFO' => 'ค่าเป็นไปได้มาจากจำนวนเงินที่ป้อนในโมดูล {{forecastByModule}}',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_LIKELY' => 'เป็นไปได้',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_BEST' => 'ดีที่สุด',
    'LBL_FORECASTS_CONFIG_WORKSHEET_SCENARIOS_WORST' => 'แย่ที่สุด',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS' => 'แสดงสถานการณ์ที่คาดการณ์ในผลรวม',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_LIKELY' => 'แสดงผลรวมของเคสที่เป็นไปได้',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_BEST' => 'แสดงผลรวมของเคสที่ดีที่สุด',
    'LBL_FORECASTS_CONFIG_PROJECTED_SCENARIOS_WORST' => 'แสดงผลรวมของเคสที่แย่ที่สุด',

    // variables config
    'LBL_FORECASTS_CONFIG_VARIABLES' => 'ตัวแปร',
    'LBL_FORECASTS_CONFIG_VARIABLES_DESC' => 'สูตรสำหรับตารางเมตริกจะอ้างอิงขั้นตอนการขายสำหรับ {{forecastByModule}} ที่จำเป็นต้องแยกจากกระบวนการขาย ได้แก่ {{forecastByModule}} ที่ปิดโดยไม่ได้รับการขาย',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_LOST_STAGE' => 'โปรดเลือกขั้นตอนการขายที่แสดงถึง {{forecastByModule}} ที่ปิดและไม่ได้รับการขาย:',
    'LBL_FORECASTS_CONFIG_VARIABLES_CLOSED_WON_STAGE' => 'โปรดเลือกขั้นตอนการขายที่แสดงถึง {{forecastByModule}} ที่ปิดและได้รับการขาย:',
    'LBL_FORECASTS_CONFIG_VARIABLES_FORMULA_DESC' => 'ดังนั้น สูตรของกระบวนการขายจะเป็น:',

    'LBL_FORECASTS_WIZARD_SUCCESS_TITLE' => 'สำเร็จ:',
    'LBL_FORECASTS_WIZARD_SUCCESS_MESSAGE' => 'คุณตั้งค่าโมดูลประมาณการสำเร็จแล้ว โปรดรอสักครู่ขณะที่โหลดโมดูล',
    'LBL_FORECASTS_TABBED_CONFIG_SUCCESS_MESSAGE' => 'บันทึกการกำหนดค่าประมาณการแล้ว โปรดรอสักครู่ขณะที่โหลดโมดูลอีกครั้ง',
    // Labels for Success Messages:
    'LBL_FORECASTS_WORKSHEET_SAVE_DRAFT_SUCCESS' => 'คุณได้บันทึกเวิร์กชีทประมาณการเป็นแบบร่างสำหรับช่วงเวลาที่เลือกไว้แล้ว',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS' => 'คุณได้คอมมิตประมาณการของคุณแล้ว',
    'LBL_FORECASTS_WORKSHEET_COMMIT_SUCCESS_TO' => 'คุณได้คอมมิตประมาณการของคุณกับ {{manager}} แล้ว',

    // custom ranges
    'LBL_FORECASTS_CUSTOM_RANGES_DEFAULT_NAME' => 'ช่วงที่กำหนดเอง',
    'LBL_UNAUTH_FORECASTS' => 'ไม่ได้รับสิทธิ์เข้าถึงการตั้งค่าประมาณการ',
    'LBL_FORECASTS_RANGES_BASED_TITLE' => 'ช่วงอ้างอิงความน่าจะเป็น',
    'LBL_FORECASTS_CUSTOM_BASED_TITLE' => 'ช่วงที่กำหนดเองอ้างอิงความน่าจะเป็น',
    'LBL_FORECASTS_CUSTOM_NO_BASED_TITLE' =>'ช่วงไม่ได้อ้างอิงความน่าจะเป็น',

    // worksheet columns config
    'LBL_DISCOUNT' => 'ส่วนลด',
    'LBL_OPPORTUNITY_STATUS' => 'สถานะของโอกาสทางการขาย',
    'LBL_OPPORTUNITY_NAME' => 'ชื่อโอกาสทางการขาย',
    'LBL_PRODUCT_TEMPLATE' => 'แคตตาล็อกผลิตภัณฑ์',
    'LBL_CAMPAIGN' => 'แคมเปญ',
    'LBL_TEAMS' => 'ทีม',
    'LBL_CATEGORY' => 'หมวดหมู่',
    'LBL_COST_PRICE' => 'ราคาต้นทุน',
    'LBL_TOTAL_DISCOUNT_AMOUNT' => 'ยอดเงินรวมส่วนลด',
    'LBL_FORECASTS_CONFIG_WORKSHEET_TEXT' => 'เลือกคอลัมน์ที่ต้องการให้ปรากฏในมุมมองของเวิร์กชีท ฟิลด์ต่อไปนี้จะเลือกไว้เป็นค่าเริ่มต้น:',

    // forecast details dashlet
    'LBL_DASHLET_FORECAST_NOT_SETUP' => 'ไม่ได้กำหนดค่าประมาณการไว้ ต้องมีการตั้งค่าประมาณการเพื่อใช้วิดเจ็ตนี้ โปรดติดต่อผู้ดูแลระบบของคุณ',
    'LBL_DASHLET_FORECAST_NOT_SETUP_ADMIN' => 'ไม่ได้กำหนดค่าประมาณการไว้ คุณต้องตั้งค่าเพื่อใช้วิดเจ็ตนี้',
    'LBL_DASHLET_FORECAST_CONFIG_LINK_TEXT' => 'โปรดคลิกที่นี่เพื่อกำหนดค่าโมดูลประมาณการ',
    'LBL_DASHLET_MY_PIPELINE' => 'กระบวนการขายของฉัน',
    'LBL_DASHLET_MY_TEAMS_PIPELINE' => "กระบวนการขายของทีม",
    'LBL_DASHLET_PIPELINE_CHART_NAME' => 'แผนภูมิกระบวนการขายของประมาณการ',
    'LBL_DASHLET_PIPELINE_CHART_DESC' => 'แสดงแผนภูมิกระบวนการขายปัจจุบัน',
    'LBL_FORECAST_DETAILS_DEFICIT' => 'ติดลบ',
    'LBL_FORECAST_DETAILS_SURPLUS' => 'ส่วนเกิน',
    'LBL_FORECAST_DETAILS_SHORT' => 'ขาดไป',
    'LBL_FORECAST_DETAILS_EXCEED' => 'เกินมา',
    'LBL_FORECAST_DETAILS_NO_DATA' => 'ไม่มีข้อมูล',
    'LBL_FORECAST_DETAILS_MEETING_QUOTA' => 'โควตาการประชุม',

    'LBL_ASSIGN_QUOTA_BUTTON' => 'ระบุโควตา',
    'LBL_ASSIGNING_QUOTA' => 'กำลังระบุโควตา',
    'LBL_QUOTA_ASSIGNED' => 'ระบุโควตาสำเร็จแล้ว',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_TITLE' => 'ข้อผิดพลาดในการเข้าถึงประมาณการ',
    'LBL_FORECASTS_NO_ACCESS_TO_CFG_MSG' => 'คุณไม่มีสิทธิ์ในการกำหนดค่าประมาณการ โปรดติดต่อผู้ดูแลระบบของคุณ',
    'WARNING_DELETED_RECORD_RECOMMIT_1' => 'ระเบียนนี้รวมอยู่ใน ',
    'WARNING_DELETED_RECORD_RECOMMIT_2' => 'ระบบจะนำออกและคุณจะต้องคอมมิตอีกครั้งสำหรับ ',

    'LBL_DASHLET_MY_FORECAST' => 'ประมาณการของฉัน',
    'LBL_DASHLET_MY_TEAMS_FORECAST' => "ประมาณการของทีม",

    'LBL_WARN_UNSAVED_CHANGES_CONFIRM_SORT' => 'คุณมีการเปลี่ยนแปลงที่ยังไม่ได้บันทึก คุณแน่ใจหรือไม่ว่าต้องการจัดเรียงเวิร์กชีทและยกเลิกการเปลี่ยนแปลง',

    // Forecasts Records View Help Text
    'LBL_HELP_RECORDS' => 'โมดูล {{plural_module_name}} เป็นการรวมระเบียน {{forecastby_singular_module}} เพื่อสร้าง {{forecastworksheets_module}} และคาดการณ์การขาย ผู้ใช้สามารถทำงานตาม {{quotas_module}} ของการขายได้ในระดับบุคคล ทีม และองค์กรการขาย ก่อนที่ผู้ใช้จะสามารถเข้าถึงโมดูล {{plural_module_name}} ผู้ดูแลระบบต้องเลือกช่วงเวลา ช่วง และสถานการณ์ที่ต้องการขององค์กรก่อน

ตัวแทนขายจะใช้โมดูล {{plural_module_name}} เพื่อทำงานกับ {{forecastby_module}} ที่ได้รับการระบุไปตามช่วงเวลาปัจจุบัน ผู้ใช้เหล่านี้จะคอมมิตการคาดการณ์รวมสำหรับการขายส่วนตัวโดยพิจารณาจาก {{forecastby_module}} ที่คาดหมายว่าจะปิดได้ ผู้จัดการฝ่ายขายจะทำงานกับระเบียน {{forecastby_singular_module}} ของตนเองในลักษณะเดียวกับตัวแทนขายคนอื่นๆ นอกจากนี้ ยังจะรวมจำนวนเงินที่คอมมิตของผู้รับรายงานแต่ละคนเพื่อคาดการณ์การขายโดยรวมของทีม และทำงานตามโควตาของทีมสำหรับแต่ละช่วงเวลา นอกจากนี้ยังมีข้อมูลเชิงลึกเพิ่มเติมจากองค์ประกอบของแผงข้อมูลเชิงวิเคราะห์ที่ขยายได้ รวมถึงการวิเคราะห์เวิร์กชีทของบุคคลแต่ละคน และการวิเคราะห์เวิร์กชีททีมของผู้จัดการ'
);
