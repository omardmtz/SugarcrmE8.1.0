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
/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
global $sugar_config;

$mod_strings = array (
// OOTB Scheduler Job Names:
'LBL_OOTB_WORKFLOW'		=> 'Καθήκοντα Διεργασιών Ροής Εργασίας',
'LBL_OOTB_REPORTS'		=> 'Εκτέλεση Αναφοράς Συστήματος Χρονοπρογραμματισμένων Εργασιών',
'LBL_OOTB_IE'			=> 'Έλεγχος Εισερχόμενων Ταχυδρομικών Θυρίδων',
'LBL_OOTB_BOUNCE'		=> 'Εκτέλεση Διαδικασίας Νυχτερινών Ακάλυπτων Emails Εκστρατείας',
'LBL_OOTB_CAMPAIGN'		=> 'Εκτέλεση Νυχτερινών Μαζικών Emails Εκστρατείας',
'LBL_OOTB_PRUNE'		=> 'Περιορισμός Βάσης Δεδομένων την 1η του Μήνα',
'LBL_OOTB_TRACKER'		=> 'Περιορισμός Πινάκων Σημείου Εντοπισμού',
'LBL_OOTB_PRUNE_RECORDLISTS'		=> 'Κλαδέψτε Λίστες Παλαιών Εγγραφών',
'LBL_OOTB_REMOVE_TMP_FILES' => 'Κατάργηση προσωρινών αρχείων',
'LBL_OOTB_REMOVE_DIAGNOSTIC_FILES' => 'Κατάργηση εργαλείων διαγνωστικών αρχείων',
'LBL_OOTB_REMOVE_PDF_FILES' => 'Κατάργηση προσωρινών PDF αρχείων',
'LBL_UPDATE_TRACKER_SESSIONS' => 'Αναβάθμιση Πίνακα tracker_sessions',
'LBL_OOTB_SEND_EMAIL_REMINDERS' => 'Αποστείλετε ειδοποιήσεις υπενθύμισης μέσω ηλεκτρονικού ταχυδρομείου',
'LBL_OOTB_CLEANUP_QUEUE' => 'Εκκαθάριση Εργασιών Ουράς Αναμονής',
'LBL_OOTB_CREATE_NEXT_TIMEPERIOD' => 'Δημιουργία Μελλοντικών Χρονικών Περιόδων',
'LBL_OOTB_HEARTBEAT' => 'Sugar Heartbeat',
'LBL_OOTB_KBCONTENT_UPDATE' => 'Update KBContent articles.',
'LBL_OOTB_KBSCONTENT_EXPIRE' => 'Άρθρα εγκεκριμένα προς δημοσίευση & Άρθρα ληγμένου KB.',
'LBL_OOTB_PROCESS_AUTHOR_JOB' => 'Προγραμματισμένη εργασία στην Advanced Workflow',
'LBL_OOTB_TEAM_SECURITY_DENORM_REBUILD' => 'Αναδόμηση απομαλοποιημένων δεδομένων ασφαλείας ομάδας',

// List Labels
'LBL_LIST_JOB_INTERVAL' => 'Διάστημα:',
'LBL_LIST_LIST_ORDER' => 'Χρονοπρογραμματιστές εργασιών:',
'LBL_LIST_NAME' => 'Χρονοπρογραμματιστής εργασιών:',
'LBL_LIST_RANGE' => 'Εύρος Λίστας:',
'LBL_LIST_REMOVE' => 'Κατάργηση:',
'LBL_LIST_STATUS' => 'Κατάσταση',
'LBL_LIST_TITLE' => 'Λίστα Χρονοπρογραμματισμών:',
'LBL_LIST_EXECUTE_TIME' => 'Θα Εκτελέσει Την:',
// human readable:
'LBL_SUN'		=> 'Κυριακή',
'LBL_MON'		=> 'Δευτέρα',
'LBL_TUE'		=> 'Τρίτη',
'LBL_WED'		=> 'Τετάρτη',
'LBL_THU'		=> 'Πέμπτη',
'LBL_FRI'		=> 'Παρασκευή',
'LBL_SAT'		=> 'Σάββατο',
'LBL_ALL'		=> 'Κάθε Μέρα',
'LBL_EVERY_DAY'	=> 'Κάθε Μέρα',
'LBL_AT_THE'	=> 'Κατά την',
'LBL_EVERY'		=> 'Κάθε',
'LBL_FROM'		=> 'Από',
'LBL_ON_THE'	=> 'Στις',
'LBL_RANGE'		=> 'στο',
'LBL_AT' 		=> 'στις',
'LBL_IN'		=> 'σε',
'LBL_AND'		=> 'και',
'LBL_MINUTES'	=> 'λεπτά',
'LBL_HOUR'		=> 'ώρες',
'LBL_HOUR_SING'	=> 'ώρα',
'LBL_MONTH'		=> 'μήνα',
'LBL_OFTEN'		=> 'Όσο το δυνατόν συχνότερα.',
'LBL_MIN_MARK'	=> 'σήμα λεπτού',


// crontabs
'LBL_MINS' => 'λεπ.',
'LBL_HOURS' => 'ώρ.',
'LBL_DAY_OF_MONTH' => 'ημερομηνία',
'LBL_MONTHS' => 'μήν.',
'LBL_DAY_OF_WEEK' => 'ημέρα',
'LBL_CRONTAB_EXAMPLES' => 'Το παραπάνω χρησιμοποιεί πρότυπο σημείωσης crontab.',
'LBL_CRONTAB_SERVER_TIME_PRE' =>  'Οι προδιαγραφές του Cron εκτελούνται με βάση την ζώνη ώρας του διακομιστή (',
'LBL_CRONTAB_SERVER_TIME_POST' => '). Παρακαλώ προσδιορίστε αναλόγως το χρονοδιάγραμμα εκτέλεσης εργασιών.',
// Labels
'LBL_ALWAYS' => 'Πάντα',
'LBL_CATCH_UP' => 'Εκτέλεση Αν Λείπει',
'LBL_CATCH_UP_WARNING' => 'Απο-ελέγξετε, αν αυτή η εργασία μπορεί να διαρκέσει περισσότερο από ένα λεπτό για να τρέξει.',
'LBL_DATE_TIME_END' => 'Ημερομηνία και Ώρα Λήξης',
'LBL_DATE_TIME_START' => 'Ημερομηνία και Ώρα Έναρξης',
'LBL_INTERVAL' => 'Διάστημα',
'LBL_JOB' => 'Εργασία',
'LBL_JOB_URL' => 'URL Εργασίας',
'LBL_LAST_RUN' => 'Τελευταία Επιτυχής Εκτέλεση',
'LBL_MODULE_NAME' => 'Χρονοπρογραμματιστής εργασιών Sugar',
'LBL_MODULE_NAME_SINGULAR' => 'Χρονοπρογραμματιστής εργασιών Sugar',
'LBL_MODULE_TITLE' => 'Χρονοπρογραμματιστές εργασιών',
'LBL_NAME' => 'Όνομα Εργασίας',
'LBL_NEVER' => 'Ποτέ',
'LBL_NEW_FORM_TITLE' => 'Νέος Χρονοπρογραμματισμός',
'LBL_PERENNIAL' => 'διαρκής',
'LBL_SEARCH_FORM_TITLE' => 'Αναζήτηση Χρονοπρογραμματιστή εργασιών',
'LBL_SCHEDULER' => 'Χρονοπρογραμματιστής εργασιών:',
'LBL_STATUS' => 'Κατάσταση',
'LBL_TIME_FROM' => 'Ενεργή Από',
'LBL_TIME_TO' => 'Ενεργή Σε',
'LBL_WARN_CURL_TITLE' => 'cURL Προειδοποίηση:',
'LBL_WARN_CURL' => 'Προειδοποίηση:',
'LBL_WARN_NO_CURL' => 'Αυτό το σύστημα δεν έχει το cURL βιβλιοθηκών ενεργοποιημένο/συνταγμένο στην ενότητα PHP (--with-curl=/path/to/curl_library). Παρακαλώ επικοινωνήστε με τον διαχειριστή σας, για να επιλύσετε αυτό το ζήτημα. Χωρίς την λειτουργικότητα cURL, οι Χρονοπρογραμματιστές εργασιών δεν μπορούν να περάσουν να εκτελεστούν.',
'LBL_BASIC_OPTIONS' => 'Βασική Εγκατάσταση',
'LBL_ADV_OPTIONS'		=> 'Σύνθετες Επιλογές',
'LBL_TOGGLE_ADV' => 'Εμφάνιση Σύνθετων Επιλογών',
'LBL_TOGGLE_BASIC' => 'Εμφάνιση Βασικών Επιλογών',
// Links
'LNK_LIST_SCHEDULER' => 'Χρονοπρογραμματιστές εργασιών',
'LNK_NEW_SCHEDULER' => 'Δημιουργία Χρονοπρογραμματιστών',
'LNK_LIST_SCHEDULED' => 'Χρονοπρογραμματισμένες Εργασίες',
// Messages
'SOCK_GREETING' => "Αυτή είναι η διεπαφή για τους Χρονοπρογραμματιστές Υπηρεσιών του SugarCRM. [ Διαθέσιμες εντολές δαίμονα: έναρξη|επανεκκίνηση|κλείσιμο|κατάσταση ] Για να εγκαταλείψετε, πληκτρολογήστε \"εγκαταλείπω\". Για να κλείσει η υπηρεσία πατήστε \"κλείσιμο\".",
'ERR_DELETE_RECORD' => 'Πρέπει να προσδιορίσετε αριθμό εγγραφής για να διαγράψετε το χρονοδιάγραμμα.',
'ERR_CRON_SYNTAX' => 'Μη έγκυρο Cron σύνταξης',
'NTC_DELETE_CONFIRMATION' => 'Είστε βέβαιοι ότι θέλετε να διαγράψετε αυτή την εγγραφή;',
'NTC_STATUS' => 'Προσδιορίστε την κατάσταση στη θέση Ανενεργή για να καταργήσετε το χρονοδιάγραμμα από την αναδυόμενη λίστα Χρονοπρογραμματιστής Εργασιών.',
'NTC_LIST_ORDER' => 'Προσδιορίστε  την σειρά εμφάνισης του χρονοδιαγράμματος που θα εμφανιστεί στην αναδυόμενη λίστα Χρονοπρογραμματιστής Εργασιών.',
'LBL_CRON_INSTRUCTIONS_WINDOWS' => 'Εγκατάσταση Χρονοπρογραμματιστή Εργασιών των Windows',
'LBL_CRON_INSTRUCTIONS_LINUX' => 'Για Εγκατάσταση Crontab',
'LBL_CRON_LINUX_DESC' => 'Σημείωση: Προκειμένου να εκτελέσετε τους Χρονοπρογραμματιστές Εργασιών του Sugar, προσθέστε την ακόλουθη γραμμή στο αρχείο crontab:',
'LBL_CRON_WINDOWS_DESC' => 'Σημείωση: Προκειμένου να εκτελέσετε τους Χρονοπρογραμματιστές Εργασιών του Sugar, δημιουργήστε μία στίβα αρχείου να εκτελεί, χρησιμοποιώντας στα Windows τους Χρονοπρογραμματιστές Εργασιών. Η στίβα φακέλου πρέπει να περιλαμβάνει τις ακόλουθες εντολές:',
'LBL_NO_PHP_CLI' => 'If your host does not have the PHP binary available, you can use wget or curl to launch your Jobs.<br>for wget: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;wget --quiet --non-verbose '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1</b><br>for curl: <b>*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;*&nbsp;&nbsp;&nbsp;&nbsp;curl --silent '.$sugar_config['site_url'].'/cron.php > /dev/null 2>&1',
// Subpanels
'LBL_JOBS_SUBPANEL_TITLE'	=> '<b>Σύνδεση Εργασίας</b>',
'LBL_EXECUTE_TIME'			=> '<b>Χρόνος Εκτέλεσης</b>',

//jobstrings
'LBL_REFRESHJOBS' => '<b>Ανανέωση Εργασιών</b>',
'LBL_POLLMONITOREDINBOXES' => '<b>Έλεγχος Εισερχόμενων Λογαριασμών Ταχυδρομείου</b>',
'LBL_PERFORMFULLFTSINDEX' => '<b>Πλήρης-κείμενο Αναζήτηση Ευρετηρίου Συστήματος</b>',
'LBL_SUGARJOBREMOVEPDFFILES' => 'Κατάργηση προσωρινών αρχείων PDF',
'LBL_SUGARJOBKBCONTENTUPDATEARTICLES' => 'Δημοσιεύστε τα εγκεκριμένα άρθρα και τα ληγμένα άρθρα KB.',
'LBL__SUGARCRM_SUGARCRM_ELASTICSEARCH_QUEUE_SCHEDULER' => 'Προγραμματιστής σειράς προτεραιότητας του Elasticsearch',
'LBL_SUGARJOBREMOVEDIAGNOSTICFILES' => 'Κατάργηση αρχείων διαγνωστικού εργαλείου',
'LBL_SUGARJOBREMOVETMPFILES' => 'Κατάργηση προσωρινών αρχείων',
'LBL_SUGARCRM_SUGARCRM_DENORMALIZATION_TEAMSECURITY_JOB_REBUILDJOB' => 'Αναδόμηση απομαλοποιημένων δεδομένων ασφαλείας ομάδας',

'LBL_RUNMASSEMAILCAMPAIGN' => '<b>Εκτέλεση Νυχτερινών Μαζικών Emails Εκστρατείας</b>',
'LBL_ASYNCMASSUPDATE' => 'Εκτέλεση Ασύγχρονων Μαζικών Ενημερώσεων',
'LBL_POLLMONITOREDINBOXESFORBOUNCEDCAMPAIGNEMAILS' => '<b>Εκτέλεση Διαδικασίας Νυχτερινών Ακάλυπτων Emails Εκστρατείας</b>',
'LBL_PRUNEDATABASE' => '<b>Περιορισμός Βάσης Δεδομένων την 1η του Μήνα</b>',
'LBL_TRIMTRACKER' => '<b>Περιορισμός Πινάκων Σημείου Εντοπισμού</b>',
'LBL_PROCESSWORKFLOW' => '<b>Διαδικασία Ροής Εργασιών</b>',
'LBL_PROCESSQUEUE' => '<b>Εκτέλεση Αναφοράς Χρονοπρογραμματισμένων Εργασιών</b>',
'LBL_UPDATETRACKERSESSIONS' => '<b>Αναπροσαρμογή Πινάκων Χρόνου Συνεδριών Σημείου Εντοπισμού</b>',
'LBL_SUGARJOBCREATENEXTTIMEPERIOD' => 'Δημιουργία Μελλοντικών TimePeriods',
'LBL_SUGARJOBHEARTBEAT' => 'Sugar Heartbeat',
'LBL_SENDEMAILREMINDERS'=> 'Εκτελέστε αποστολή υπενθύμισης μέσω ηλεκτρονικού ταχυδρομείου',
'LBL_CLEANJOBQUEUE' => '<b>Εκκαθάριση Εργασιών Ουράς Αναμονής</b>',
'LBL_CLEANOLDRECORDLISTS' => 'Εκκαθάριση Λιστών Παλαιών Εγγραφών',
'LBL_PMSEENGINECRON' => 'Χρονοπρογραμματιστής εργασιών Advanced Workflow',
);

