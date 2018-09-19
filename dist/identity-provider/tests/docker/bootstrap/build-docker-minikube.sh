#!/bin/bash

# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

UPDATE_SAML_REGISTRY=0
UPDATE_LDAP_REGISTRY=0

while :
do
    case "$1" in
        -t)
            MANGO_ARCHIVE_PATH="$2"
            shift 2
            ;;
        -k)
            SUGAR_LICENSE_KEY="$2"
            shift 2
            ;;
        --update-saml-registry)
            UPDATE_SAML_REGISTRY=1
            shift 1
            ;;
        --update-ldap-registry)
            UPDATE_LDAP_REGISTRY=1
            shift 1
            ;;
        *)
            break
            ;;
    esac
done

if [[ -z ${MANGO_ARCHIVE_PATH} ]]
then
    printf "Please set up mango zip file: -t /var/tmp/SugarEnt.zip \n\n"
    exit
fi

CURRENT_DIR=$PWD
SCRIPT_DIR="$(dirname "${BASH_SOURCE[0]}")"/
cd ${SCRIPT_DIR}
SCRIPT_DIR=$PWD

#minikube start
#sleep 5
eval $(minikube docker-env --shell bash)

docker network create --driver bridge mango-install-net

docker run --name=behat-tests-env-elastic --network=mango-install-net -p 9200:9200 \
-e "http.host=0.0.0.0" -e "transport.host=127.0.0.1" \
-e "xpack.security.enabled=false" -e ES_JAVA_OPTS="-Xms512m -Xmx512m" \
-d docker.elastic.co/elasticsearch/elasticsearch:5.4.3

# Install LDAP
if [[ ${UPDATE_LDAP_REGISTRY} -eq 1 ]]
then
    cd ../openldap
    docker build -t registry.sugarcrm.net/identity-provider/idm-open-ldap:latest .
    docker push registry.sugarcrm.net/identity-provider/idm-open-ldap:latest
    cd ${SCRIPT_DIR}
fi

# Build SAML containers and update SAML registry
if [[ ${UPDATE_SAML_REGISTRY} -eq 1 ]]
then
    cd ../saml
    docker build --tag registry.sugarcrm.net/identity-provider/samlserver:latest .
    docker push registry.sugarcrm.net/identity-provider/samlserver:latest

    cd ../saml-test
    docker build --tag registry.sugarcrm.net/identity-provider/samlserver-test:latest .
    docker push registry.sugarcrm.net/identity-provider/samlserver-test:latest
    cd ${SCRIPT_DIR}
fi

cd mango

# building initial image
if [ ! -d "sugarcrm" ]
then
    mkdir sugarcrm
fi

unzip ${MANGO_ARCHIVE_PATH} -d sugarcrm 1> /dev/null
cd sugarcrm
cp -Rp Sugar*/. . && rm -rf Sugar*
cd -

docker build -t mango-built ./
rm -rf sugarcrm
cd ../

# install
docker run --name=mango-built --network=mango-install-net \
-e SUGAR_LICENSE_KEY=${SUGAR_LICENSE_KEY} \
-p 8082:80 -d mango-built

sleep 10
docker exec -it mango-built curl "http://localhost/install.php?goto=SilentInstall&cli=true"
docker commit mango-built mango-installed

# cleanup services
docker rm -fv mango-built
docker rm -fv behat-tests-env-elastic
docker network rm mango-install-net

# Build idm image as a Behat tests container and executor
cd ../../..
docker build -t idm -f Dockerfile.local .

eval $(minikube docker-env --shell bash -u)
cd ${CURRENT_DIR}
