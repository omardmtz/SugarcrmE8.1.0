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

$schedule_xml = <<<EXM
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
 <securityContext>
      <webExID></webExID>
      <password></password>
      <siteID></siteID>
      <siteName></siteName>
      <partnerID></partnerID>
    </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.CreateMeeting">
         <accessControl>
            <meetingPassword></meetingPassword>
         </accessControl>
         <metaData>
            <confName></confName>
            <agenda></agenda>
         </metaData>
         <participants>
            <maxUserNumber>0</maxUserNumber>
            <attendees>
            </attendees>
         </participants>
         <enableOptions>
            <chat>true</chat>
            <poll>true</poll>
            <audioVideo>true</audioVideo>
         </enableOptions>
         <schedule>
            <startDate></startDate>
            <openTime></openTime>
            <joinTeleconfBeforeHost>true</joinTeleconfBeforeHost>
            <duration></duration>
            <timeZoneID></timeZoneID>
         </schedule>
         <telephony>
            <telephonySupport>CALLIN</telephonySupport>
            <extTelephonyDescription>
            </extTelephonyDescription>
         </telephony>
      </bodyContent>
   </body>
</message>
EXM;

$unschedule_xml = <<<UNS
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
       <securityContext>
      <webExID></webExID>
      <password></password>
      <siteID></siteID>
      <siteName></siteName>
      <partnerID></partnerID>
    </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.DelMeeting">
         <meetingKey></meetingKey>
      </bodyContent>
   </body>
</message>
UNS;

$invite_xml = <<<INV
<?xml version="1.0"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
       <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
    </securityContext>
   </header>
   <body>
      <bodyContent xsi:type=
          "java:com.webex.service.binding.attendee.CreateMeetingAttendee">
          <role>ATTENDEE</role>
     </bodyContent>
   </body>
</message>
INV;

$uninvite_xml = <<<UNI
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.attendee.DelMeetingAttendee">
         <attendeeID></attendeeID>
      </bodyContent>
   </body>
</message>
UNI;

$details_xml = <<<DTL
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent xsi:type="java:com.webex.service.binding.meeting.GetMeeting">
         <meetingKey></meetingKey>
      </bodyContent>
   </body>
</message>
DTL;

$listmeeting_xml = <<<LST
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.LstsummaryMeeting">
         <listControl>
            <startFrom>1</startFrom>
            <maximumNum></maximumNum>
            <listMethod>OR</listMethod>
         </listControl>
         <order>
            <orderBy>HOSTWEBEXID</orderBy>
            <orderAD>ASC</orderAD>
            <orderBy>CONFNAME</orderBy>
            <orderAD>ASC</orderAD>
            <orderBy>STARTTIME</orderBy>
            <orderAD>ASC</orderAD>
         </order>
      </bodyContent>
   </body>
</message>
LST;

$joinmeeting_xml = <<<JMT
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.GetjoinurlMeeting">
         <sessionKey></sessionKey>
         <attendeeName></attendeeName>
      </bodyContent>
   </body>
</message>
JMT;

$hostmeeting_xml = <<<HST
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.GethosturlMeeting">
         <sessionKey></sessionKey>
      </bodyContent>
   </body>
</message>
HST;

$edit_xml = <<<EDT
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
      <securityContext>
         <webExID></webExID>
         <password></password>
         <siteID></siteID>
         <siteName></siteName>
         <partnerID></partnerID>
      </securityContext>
   </header>
   <body>
      <bodyContent
          xsi:type="java:com.webex.service.binding.meeting.SetMeeting">
         <metaData>
            <confName></confName>
            <agenda></agenda>
         </metaData>
         <accessControl>
            <meetingPassword></meetingPassword>
         </accessControl>
         <participants>
            <maxUserNumber>1</maxUserNumber>
            <attendees></attendees>
         </participants>
         <enableOptions>
            <chat>true</chat>
            <poll>true</poll>
            <audioVideo>true</audioVideo>
         </enableOptions>
         <schedule>
            <startDate></startDate>
            <duration></duration>
            <timeZoneID></timeZoneID>
         </schedule>
         <telephony>
            <telephonySupport>CALLIN</telephonySupport>
            <extTelephonyDescription>
            </extTelephonyDescription>
         </telephony>
         <remind>
            <enableReminder>false</enableReminder>
         </remind>
         <attendeeOptions>
            <auto>false</auto>
         </attendeeOptions>
         <meetingkey></meetingkey>
      </bodyContent>
   </body>
</message>
EDT;

$getuser_xml = <<<EXM
<?xml version="1.0" encoding="ISO-8859-1"?>
<message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <header>
 <securityContext>
      <webExID></webExID>
      <password></password>
      <siteID></siteID>
      <siteName></siteName>
      <partnerID></partnerID>
    </securityContext>
   </header>
   <body>
      <bodyContent xsi:type="java:com.webex.service.binding.user.GetUser">
          <webExId></webExId>
      </bodyContent>
   </body>
</message>
EXM;
