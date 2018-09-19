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
 * Sugar mailer
 * @api
 * @deprecated 7.0.0    Use the Mailer module instead.
 */
class SugarPHPMailer extends PHPMailerProxy
{
    var $oe; // OutboundEmail
    var $protocol = "tcp://";
    var $preppedForOutbound = false;
    var $disclosureEnabled;
    var $disclosureText;
    var $isHostEmpty = false;
    var $opensslOpened = true;

    /**
     * Sole constructor
     *
     * Doesn't call PHPMailerProxy's constructor so that using PHPMailer with exceptions is not imposed on
     * SugarPHPMailer.
     */
    public function __construct()
    {
        global $locale;
        global $current_user;
        global $sugar_config;

        $admin = new Administration();
        $admin->retrieveSettings();

        if(isset($admin->settings['disclosure_enable']) && !empty($admin->settings['disclosure_enable'])) {
            $this->disclosureEnabled = true;
            $this->disclosureText = $admin->settings['disclosure_text'];
        }

        $this->oe = new OutboundEmail();
        $this->oe->getUserMailerSettings($current_user);

        $this->setLanguage('en', 'vendor/phpmailer/phpmailer/language/');
        $this->PluginDir	= 'vendor/phpmailer/phpmailer/';
        $this->Mailer	 	= 'smtp';

        // cn: i18n
        $this->CharSet		= $locale->getPrecedentPreference('default_email_charset');
        $this->Encoding		= 'quoted-printable';
        $this->isHTML(false);  // default to plain-text email
        $this->Hostname = $sugar_config['host_name'];
        $this->WordWrap		= 996;
        // cn: gmail fix
        $this->protocol = ($this->oe->mail_smtpssl == 1) ? "ssl://" : $this->protocol;

        // allow for empty messages to go out
        $this->AllowEmpty = true;

    }


    /**
     * Prefills outbound details
     */
    public function setMailer() {
        global $current_user;

        $oe = new OutboundEmail();
        $oe = $oe->getUserMailerSettings($current_user);

        // ssl or tcp - keeping outside isSMTP b/c a default may inadvertently set ssl://
        $this->protocol = ($oe->mail_smtpssl) ? "ssl://" : "tcp://";

        if($oe->mail_sendtype == "SMTP")
        {
            //Set mail send type information
            $this->Mailer = "smtp";
            $this->Host = $oe->mail_smtpserver;
            $this->Port = $oe->mail_smtpport;
            if ($oe->mail_smtpssl == 1) {
                $this->SMTPSecure = 'ssl';
            } // if
            if ($oe->mail_smtpssl == 2) {
                $this->SMTPSecure = 'tls';
            } // if

            if($oe->mail_smtpauth_req) {
                $this->SMTPAuth = true;
                $this->Username = $oe->mail_smtpuser;
                $this->Password = $oe->mail_smtppass;
            }
        }
        else
            $this->Mailer = "sendmail";
    }

    /**
     * Prefills mailer for system
     */
    public function setMailerForSystem() {
        $oe = new OutboundEmail();
        $oe = $oe->getSystemMailerSettings();

        // ssl or tcp - keeping outside isSMTP b/c a default may inadvertantly set ssl://
        $this->protocol = ($oe->mail_smtpssl) ? "ssl://" : "tcp://";

        if($oe->mail_sendtype == "SMTP")
        {
            //Set mail send type information
            $this->Mailer = "smtp";
            $this->Host = $oe->mail_smtpserver;
            $this->Port = $oe->mail_smtpport;
            if ($oe->mail_smtpssl == 1) {
                $this->SMTPSecure = 'ssl';
            } // if
            if ($oe->mail_smtpssl == 2) {
                $this->SMTPSecure = 'tls';
            } // if
            if($oe->mail_smtpauth_req) {
                $this->SMTPAuth = true;
                $this->Username = $oe->mail_smtpuser;
                $this->Password = $oe->mail_smtppass;
            }
        }
        else
            $this->Mailer = "sendmail";
    }

    /**
     * handles Charset translation for all visual parts of the email.
     * @param string charset Default = ''
     */
    public function prepForOutbound() {
        global $locale;

        if($this->preppedForOutbound == false) {
            //bug 28534. We should not set it to true to circumvent the following conversion as each email is independent.
            $OBCharset = $locale->getPrecedentPreference('default_email_charset');

            // handle disclosure
            if($this->disclosureEnabled) {
                $this->Body .= "<br />&nbsp;<br />{$this->disclosureText}";
                $this->AltBody .= "\r\r{$this->disclosureText}";
            }

            // body text
            $this->Body		= from_html($locale->translateCharset(trim($this->Body), 'UTF-8', $OBCharset));
            $this->AltBody		= from_html($locale->translateCharset(trim($this->AltBody), 'UTF-8', $OBCharset));
            $subjectUTF8		= from_html(trim($this->Subject));
            $subject			= $locale->translateCharset($subjectUTF8, 'UTF-8', $OBCharset);
            $this->Subject		= $locale->translateCharset($subjectUTF8, 'UTF-8', $OBCharset);

            // HTML email RFC compliance
            if($this->ContentType == "text/html") {
                if(strpos($this->Body, '<html') === false) {

                    $langHeader = get_language_header();

                    $head=<<<eoq
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" {$langHeader}>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset={$OBCharset}" />
<title>{$subject}</title>
</head>
<body>
eoq;
                    $this->Body = $head.$this->Body."</body></html>";
                }
            }

            // Headers /////////////////////////////////
            // the below is done in PHPMailer::CreateHeader();
            $this->FromName		= $locale->translateCharset(trim($this->FromName), 'UTF-8', $OBCharset);
        }
    }

    /**
     * Replace images with locations specified by regex with cid: images
     * and attach needed files
     * @param string $regex Regular expression
     * @param string $local_prefix Prefix where local files are stored
     * @param bool $object Use attachment object
     */
    public function replaceImageByRegex($regex, $local_prefix, $object = false)
    {
        preg_match_all("#<img[^>]*[\s]+src[^=]*=[\s]*[\"']($regex)(.+?)[\"']#si", $this->Body, $matches);
        $i = 0;
        foreach($matches[2] as $match) {
            $filename = urldecode($match);
            $cid = $filename;
            $file_location = $local_prefix.$filename;
            if(!file_exists($file_location)) continue;
            if($object) {
                if(preg_match('#&(?:amp;)?type=([\w]+)#i', $matches[0][$i], $typematch)) {
                    switch(strtolower($typematch[1])) {
                        case 'documents':
                            $beanname = 'DocumentRevisions';
                            break;
                        case 'notes':
                            $beanname = 'Notes';
                            break;
                    }
                }
                $mime_type = "application/octet-stream";
                if(isset($beanname)) {
                    $bean = SugarModule::get($beanname)->loadBean();
                    $bean->retrieve($filename);
                    if(!empty($bean->id)) {
                        $mime_type = $bean->file_mime_type;
                        $filename = $bean->filename;
                    }
                }
            } else {
                $mime_type = "image/".strtolower(pathinfo($filename, PATHINFO_EXTENSION));
            }
            $this->addEmbeddedImage($file_location, $cid, $filename, 'base64', $mime_type);
            $i++;
        }
        //replace references to cache with cid tag
        $this->Body = preg_replace("|\"$regex|i",'"cid:',$this->Body);
        // remove bad img line from outbound email
        $this->Body = preg_replace('#<img[^>]+src[^=]*=\"\/([^>]*?[^>]*)>#sim', '', $this->Body);
    }

    /**
     * @param notes	array of note beans
     */
    public function handleAttachments($notes) {
        global $sugar_config;

		// cn: bug 4864 - reusing same SugarPHPMailer class, need to clear attachments
		$this->clearAttachments();

        if (empty($notes)) {
            return;
        }
		//replace references to cache/images with cid tag
        $this->Body = preg_replace(';=\s*"'.preg_quote(sugar_cached('images/'), ';').';','="cid:',$this->Body);

        $this->replaceImageByRegex("(?:{$sugar_config['site_url']})?/?cache/images/", sugar_cached("images/"));

        //Replace any embeded images using the secure entryPoint for src url.
        $this->replaceImageByRegex("(?:{$sugar_config['site_url']})?index.php[?]entryPoint=download&(?:amp;)?[^\"]+?id=", "upload://", true);

        //Handle regular attachments.
        foreach($notes as $note) {
            $mime_type = 'text/plain';
            $file_location = '';
            $filename = '';

            if($note->object_name == 'Note') {
                if (! empty($note->file->temp_file_location) && is_file($note->file->temp_file_location)) {
                    $file_location = $note->file->temp_file_location;
                    $filename = $note->file->original_file_name;
                    $mime_type = $note->file->mime_type;
                } else {
                    $file_location = "upload://{$note->id}";
                    $filename = $note->id.$note->filename;
                    $mime_type = $note->file_mime_type;
                }
            } elseif($note->object_name == 'DocumentRevision') { // from Documents
                $filename = $note->id.$note->filename;
                $file_location = "upload://$filename";
                $mime_type = $note->file_mime_type;
            }

            $filename = substr($filename, 36, strlen($filename)); // strip GUID	for PHPMailer class to name outbound file
            if (!$note->embed_flag) {
                $this->addAttachment($file_location, $filename, 'base64', $mime_type);
            } // else
        }
    }

    /**
     * Overloads PHPMailer::SmtpConnect() to log the correct error message based on the email configuration used for
     * connecting to the SMTP server.
     *
     * @param array $options
     * @return bool
     */
    public function smtpConnect($options = array()) {
		$connection = parent::smtpConnect($options);
		if (!$connection) {
			global $app_strings;
			if(isset($this->oe) && $this->oe->type == "system") {
				$this->setError($app_strings['LBL_EMAIL_INVALID_SYSTEM_OUTBOUND']);
			} else {
				$this->setError($app_strings['LBL_EMAIL_INVALID_PERSONAL_OUTBOUND']);
			} // else
		}
		return $connection;
	} // fn
} // end class definition
