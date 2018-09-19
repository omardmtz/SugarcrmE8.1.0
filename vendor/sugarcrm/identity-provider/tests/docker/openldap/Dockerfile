# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

FROM centos:centos6

# See possible debug levels in man page (loglevel): http://linux.die.net/man/5/slapd.conf
ENV DEBUG_LEVEL=256
EXPOSE 389 636
VOLUME [ "/data" ]

# Update to last version

RUN yum -y update && \
    yum -y install openldap-servers openldap-clients && \
    yum clean all

COPY . /root

CMD ["/root/run.sh"]
