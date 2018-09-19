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

$connector_strings = array(
    'LBL_LICENSING_INFO' => '<table border="0" cellspacing="1"><tr><td valign="top" width="35%" class="dataLabel">
Λάβετε κλειδί API Key και Μυστικό από την Google μέσω εγγραφής του Sugar παραδείγματος σας ως νέα εφαρμογή.
<br/><br>Βήματα για εγγραφή του παραδείγματός σας:
<br/><br/>
<ol>
<li>Μπείτε στην ιστοσελίδα των προγραμματιστών της Google:
<a href=\'https://console.developers.google.com/project\'
target=\'_blank\'>https://console.developers.google.com/project</a>.</li>

<li>Συνδεθείτε μέσω του λογαριασμού Google μέσω του οποίου θέλετε να εγγράψετε την εφαρμογή.</li>
<li>Δημιουργήστε νέο έργο</li>
<li>Καταχωρήστε Όνομα έργου και κάντε κλικ στο δημιουργία.</li>
<li>Μόλις δημιουργηθεί το έργο ενεργοποιήστε τα Google Drive και Google Contacts API</li>
<li>Στην ενότητα API & Auth > Credentials δημιουργήστε νέα ταυτότητα πελάτη </li>
<li>Επιλέξτε Εφαρμογή διαδικτύου και κάντε κλικ στην απεικόνιση συγκατάθεσης Διαμόρφωσης</li>
<li>Καταχωρήστε όνομα προϊόντος και κάντε κλικ στο Αποθήκευση</li>
<li>Στην ενότητα Εξουσιοδοτημένη ανακατεύθυνση των URI καταχωρήστε το παρακάτω url: {$SITE_URL}/index.php?module=EAPM&action=GoogleOauth2Redirect</li>
<li>Κάντε κλικ στο δημιουργία ταυτότητας πελάτη</li>
<li>Αντιγράψτε την ταυτότητα πελάτη και το μυστικό πελάτη στα παρακάτω πλαίσια</li>

</li>
</ol>
</td></tr>
</table>',
    'oauth2_client_id' => 'Ταυτότητα πελάτη',
    'oauth2_client_secret' => 'Μυστικό πελάτη',
);
