#!/bin/bash

# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

set -e

if [ ! -f /data/lib/ldap/DB_CONFIG ]; then

    cp /usr/share/openldap-servers/DB_CONFIG.example /var/lib/ldap/DB_CONFIG
    chown ldap. /var/lib/ldap/DB_CONFIG

    service slapd start
    sleep 3

    ldapmodify -Y EXTERNAL -H ldapi:/// -f /root/manager.ldif
    ldapmodify -Y EXTERNAL -H ldapi:/// -f /root/domain.ldif
    ldapmodify -Y EXTERNAL -H ldapi:/// -f /root/disableAnonymousBind.ldif

    ldapadd -Q -Y EXTERNAL -H ldapi:/// -f /root/memberOf.ldif
    ldapadd -x -D cn=admin,ou=admins,dc=openldap,dc=com -w admin\&password -f /root/users.ldif

    service slapd stop
    sleep 3

    mkdir /data/lib /data/etc
    cp -ar /var/lib/ldap /data/lib
    cp -ar /etc/openldap /data/etc
fi

rm -rf /var/lib/ldap && ln -s /data/lib/ldap /var/lib/ldap
rm -rf /etc/openldap && ln -s /data/etc/openldap /etc/openldap

exec /usr/sbin/slapd -h "ldap:/// ldaps:/// ldapi:///" -u ldap -d $DEBUG_LEVEL
