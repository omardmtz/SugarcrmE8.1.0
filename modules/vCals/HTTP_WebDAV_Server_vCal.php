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


require_once 'vendor/HTTP_WebDAV_Server/Server.php';

/**
 * Filesystem access using WebDAV
 *
 * @access public
 */
class HTTP_WebDAV_Server_vCal extends HTTP_WebDAV_Server
{
        /**
         * Root directory for WebDAV access
         *
         * Defaults to webserver document root (set by ServeRequest)
         *
         * @access private
         * @var    string
         */
        var $base = "";
        var $vcal_focus;
        var $vcal_type = "";
        var $source = "";
        var $publish_key = "";

    public function __construct()
        {
           $this->vcal_focus = BeanFactory::newBean('vCals');
           $this->user_focus = BeanFactory::newBean('Users');
        }


        /**
         * Serve a webdav request
         *
         * @access public
         * @param  string
         */
        function ServeRequest($base = false)
        {

            global $sugar_config,$current_language;

            if (!empty($sugar_config['session_dir']))
            {
               session_save_path($sugar_config['session_dir']);
            }

            session_start();

            // clean_incoming_data();


            $current_language = $sugar_config['default_language'];

            // special treatment for litmus compliance test
            // reply on its identifier header
            // not needed for the test itself but eases debugging
/*
            foreach(apache_request_headers() as $key => $value) {
                if(stristr($key,"litmus")) {
                    error_log("Litmus test $value");
                    header("X-Litmus-reply: ".$value);
                }
            }
*/

            // set root directory, defaults to webserver document root if not set
            if ($base) {
                $this->base = realpath($base); // TODO throw if not a directory
            } else if(!$this->base) {
                $this->base = $_SERVER['DOCUMENT_ROOT'];
            }


            $query_arr =  array();
             // set path
            if ( empty($_SERVER["PATH_INFO"]))
            {
				$this->path = "/";
				if(strtolower($_SERVER["REQUEST_METHOD"]) == 'get'){
					$query_arr = $_REQUEST;
				}else{
					parse_str($_REQUEST['parms'],$query_arr);
				}
            } else{
              $this->path = $this->_urldecode( $_SERVER["PATH_INFO"]);

              if(ini_get("magic_quotes_gpc")) {
               $this->path = stripslashes($this->path);
              }

              $query_str = preg_replace('/^\//','',$this->path);
              $query_arr =  array();
              parse_str($query_str,$query_arr);
            }


            if ( ! empty($query_arr['type']))
            {
              $this->vcal_type = $query_arr['type'];
            }
            else {
              $this->vcal_type = 'vfb';
            }

            if ( ! empty($query_arr['source']))
            {
              $this->source = $query_arr['source'];
            }
            else {
              $this->source = 'outlook';
            }

            if ( ! empty($query_arr['key']))
            {
              $this->publish_key = $query_arr['key'];
            }

            // select user by email
            if ( ! empty($query_arr['email']))
            {


              // clean the string!
              $query_arr['email'] = clean_string($query_arr['email']);
              //get user info
              $this->user_focus->retrieve_by_email_address( $query_arr['email']);

            }
            // else select user by user_name
            else if ( ! empty($query_arr['user_name']))
            {
              // clean the string!
              $query_arr['user_name'] = clean_string($query_arr['user_name']);

              //get user info
              $arr = array('user_name'=>$query_arr['user_name']);
              $this->user_focus->retrieve_by_string_fields($arr);
            }
            // else select user by user id
            else if ( ! empty($query_arr['user_id']))
            {
                $this->user_focus->retrieve($query_arr['user_id']);
            }

            // if we haven't found a user, then return 401
            if ( empty($this->user_focus->id) || $this->user_focus->id == -1)
            {
                //set http status
                $this->http_status('401 Unauthorized');
                //send authenticate header only if this is not a freebusy request
                if (empty($_REQUEST['type']) || $_REQUEST['type'] != 'vfb'){
                    header('WWW-Authenticate: Basic realm="'.($this->http_auth_realm).'"');
                }
                return;
            }

//            if(empty($this->user_focus->user_preferences))
//            {
                     $this->user_focus->loadPreferences();
//            }

            // let the base class do all the work
            parent::ServeRequest();
        }

        /**
         * No authentication is needed here
         *
         * @access private
         * @param  string  HTTP Authentication type (Basic, Digest, ...)
         * @param  string  Username
         * @param  string  Password
         * @return bool    true on successful authentication
         */
        function check_auth($type, $user, $pass)
        {
            if(isset($_SESSION['authenticated_user_id'])) {
                // allow logged in users access to freebusy info
                return true;
            }
            if(!empty($this->publish_key) && !empty($this->user_focus) && $this->user_focus->getPreference('calendar_publish_key' ) == $this->publish_key) {
                return true;
            }
            return false;
        }


        function GET()
        {
            return true;
        }

        // {{{ http_GET()

        /**
        * GET method handler
        *
        * @param void
        * @returns void
        */
        function http_GET()
        {

           if ($this->vcal_type == 'vfb')
           {
             $this->http_status("200 OK");
             echo $this->vcal_focus->get_vcal_freebusy($this->user_focus);
           } else {
             $this->http_status("404 Not Found");
           }

        }
        // }}}


        // {{{ http_PUT()

        /**
        * PUT method handler
        *
        * @param  void
        * @return void
        */
        function http_PUT()
        {
            $options = Array();
            $options["path"] = $this->path;
            $options["content_length"] = $_SERVER["CONTENT_LENGTH"];

            // get the Content-type
            if (isset($_SERVER["CONTENT_TYPE"])) {
                // for now we do not support any sort of multipart requests
                if (!strncmp($_SERVER["CONTENT_TYPE"], "multipart/", 10)) {
                    $this->http_status("501 not implemented");
                    echo "The service does not support mulipart PUT requests";
                    return;
                }
                $options["content_type"] = $_SERVER["CONTENT_TYPE"];
            } else {
                // default content type if none given
                $options["content_type"] = "application/octet-stream";
            }

            /* RFC 2616 2.6 says: "The recipient of the entity MUST NOT
               ignore any Content-* (e.g. Content-Range) headers that it
               does not understand or implement and MUST return a 501
               (Not Implemented) response in such cases."
            */
            foreach ($_SERVER as $key => $val) {
                if (strncmp($key, "HTTP_CONTENT", 11)) continue;
                switch ($key) {
                case 'HTTP_CONTENT_ENCODING': // RFC 2616 14.11
                    // TODO support this if ext/zlib filters are available
                    $this->http_status("501 not implemented");
                    echo "The service does not support '$val' content encoding";
                    return;

                case 'HTTP_CONTENT_LANGUAGE': // RFC 2616 14.12
                    // we assume it is not critical if this one is ignored
                    // in the actual PUT implementation ...
                    $options["content_language"] = $val;
                    break;

                case 'HTTP_CONTENT_LOCATION': // RFC 2616 14.14
                    /* The meaning of the Content-Location header in PUT
                       or POST requests is undefined; servers are free
                       to ignore it in those cases. */
                    break;

                case 'HTTP_CONTENT_RANGE':    // RFC 2616 14.16
                    // single byte range requests are NOT supported
                    // the header format is also specified in RFC 2616 14.16
                    // TODO we have to ensure that implementations support this or send 501 instead
                        $this->http_status("400 bad request");
                        echo "The service does only support single byte ranges";
                        return;

                case 'HTTP_CONTENT_MD5':      // RFC 2616 14.15
                    // TODO: maybe we can just pretend here?
                    $this->http_status("501 not implemented");
                    echo "The service does not support content MD5 checksum verification";
                    return;

				case 'HTTP_CONTENT_LENGTH': // RFC 2616 14.14
                    /* The meaning of the Content-Location header in PUT
                       or POST requests is undefined; servers are free
                       to ignore it in those cases. */
                    break;

                default:
                    // any other unknown Content-* headers
                    $this->http_status("501 not implemented");
                    echo "The service does not support '$key'";
                    return;
                }
            }

            // DO AUTHORIZATION for publishing Free/busy to Sugar:
            if ( empty($this->publish_key) ||
                $this->publish_key != $this->user_focus->getPreference('calendar_publish_key' ))
            {
                    $this->http_status("401 not authorized");
                    return;

            }

            // retrieve
            $arr = array('user_id'=>$this->user_focus->id,'type'=>'vfb','source'=>$this->source);
            $this->vcal_focus->retrieve_by_string_fields($arr);

            $isUpdate  = false;

            if ( ! empty($this->vcal_focus->user_id ) &&
                $this->vcal_focus->user_id != -1 )
            {
              $isUpdate  = true;
            }

            // open input stream
            $options["stream"] = fopen("php://input", "r");
            $content = '';

            // read in input stream
            while (!feof($options["stream"]))
            {
               $content .= fread($options["stream"], 4096);
            }

            // set freebusy members and save
            $this->vcal_focus->content = $content;
            $this->vcal_focus->type = 'vfb';
            $this->vcal_focus->source = $this->source;
            $focus->date_modified = null;
            $this->vcal_focus->user_id = $this->user_focus->id;
            $this->vcal_focus->save();

            if ( $isUpdate )
            {
               $this->http_status("204 No Content");
            } else {
               $this->http_status("201 Created");
            }
        }

        /**
         * PUT method handler
         *
         * @param  array  parameter passing array
         * @return bool   true on success
         */
        function PUT(&$options)
        {

        }

        /**
         * LOCK method handler
         *
         * @param  array  general parameter passing array
         * @return bool   true on success
         */
        function lock(&$options)
        {

            $options["timeout"] = time()+300; // 5min. hardcoded
            return true;
        }

        /**
         * UNLOCK method handler
         *
         * @param  array  general parameter passing array
         * @return bool   true on success
         */
        function unlock(&$options)
        {

            return "200 OK";
        }


        /**
         * checkLock() helper
         *
         * @param  string resource path to check for locks
         * @return bool   true on success
         */
        function checkLock($path)
        {
            return false;

        }

    /**
     * set HTTP return status and mirror it in a private header
     *
     * @param  string  status code and message
     * @return void
     * @see HTTP_WebDAV_Server::http_status()
     */
    public function http_status($status)
    {
        parent::http_status($status);
        header('Content-Type: text/calendar; charset=' . $GLOBALS['sugar_config']['default_charset']);
    }
}
