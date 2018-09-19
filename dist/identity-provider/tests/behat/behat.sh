#!/usr/bin/env bash

# Your installation or use of this SugarCRM file is subject to the applicable
# terms available at
# http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
# If you do not agree to all of the applicable terms or do not have the
# authority to bind the entity as an authorized representative, then do not
# install or use this SugarCRM file.
#
# Copyright (C) SugarCRM Inc. All rights reserved.

LDAP_SERVER_URL='127.0.0.1'
SELENIUM_HOST='127.0.0.1'
DOWNLOAD_SELENIUM=0

while :
do
    case "$1" in
        -u)
            INSTANCE_URL="$2"
            shift 2
            ;;
        -s)
            BEHAT_SUITE="$2"
            shift 2
            ;;
        -l)
            LDAP_SERVER_URL="$2"
            shift 2
            ;;
        -e)
            SELENIUM_HOST="$2"
            shift 2
            ;;
        -i)
            IDP_SERVICE_URL="$2"
            shift 2
            ;;
        -h)
            HYDRA_URL="$2"
            shift 2
            ;;
        -n)
            IDM_NS="$2"
            shift 2
            ;;
        -d)
            DOWNLOAD_SELENIUM=1
            shift 1
            ;;
        *)
            break
            ;;
    esac
done

if [[ -z ${INSTANCE_URL} ]]
then
    printf "Please set up sugar url: -u http://sugar.url \n\n"
    exit
fi

if [[ -z ${BEHAT_SUITE} ]]
then
    printf "Please set up behat suite: -s saml \n\n"
    exit
fi
if [[ -z ${IDP_SERVICE_URL} ]]
then
    printf "Please set up IdP url: -i http://idp.url \n\n"
    exit
fi

cd "$(dirname "${BASH_SOURCE[0]}")"/
INSTANCE_PATH=$PWD

if [[ ${DOWNLOAD_SELENIUM} -eq 1 ]]
then
    if [[ -z $TMPDIR ]]
    then
    TMPDIR="/tmp"
    fi

    SELENIUM_URL="https://goo.gl/UzaKCo"
    SELENIUM="selenium-server-standalone-3.11.0.jar"
    SELENIUM_LOG="selenium.log"

    if [ "$(uname)" == "Darwin" ]; then
        CHROME_DRIVER_URL="https://chromedriver.storage.googleapis.com/2.36/chromedriver_mac64.zip"
    else
        CHROME_DRIVER_URL="https://chromedriver.storage.googleapis.com/2.36/chromedriver_linux64.zip"
    fi
    CHROME_DRIVER_PATH_ARCHIVE="chromedriver.zip"
    CHROME_DRIVER="chromedriver"
    CHROME_DRIVER_LOG="chromedriver.log"

    cd $TMPDIR

    if [ ! -f "$SELENIUM" ]
    then
        printf "Downloading selenium...\n\n"
        wget $SELENIUM_URL -O $SELENIUM
    fi

    if [ ! -f "$CHROME_DRIVER" ]
    then
        printf "Downloading chrome driver...\n\n"
        wget $CHROME_DRIVER_URL -O $CHROME_DRIVER_PATH_ARCHIVE
        unzip $CHROME_DRIVER_PATH_ARCHIVE        #-d cache/
    fi

    IS_CHROME_DRIVER_RUNNING=`ps -cax | grep $CHROME_DRIVER`
    if [[  -z  $IS_CHROME_DRIVER_RUNNING ]]
    then
        printf "Running chrome_driver \n\n"
        `./$CHROME_DRIVER >> $CHROME_DRIVER_LOG & `
        sleep 2
        printf "DONE \n\n"
    fi

    IS_SELENIUM_RUNNING=`ps -ax | grep "java -jar $SELENIUM" | grep -v grep| grep -v logs`
    if [[  -z  $IS_SELENIUM_RUNNING  ]]
    then
        printf "Running selenium \n\n"
        echo "java -jar $SELENIUM 2>> $SELENIUM_LOG &"
        java -jar $SELENIUM 2>> $SELENIUM_LOG &
        sleep 2
        printf "DONE \n\n"
    fi
fi

cd $INSTANCE_PATH

cat behat.yml.template | \
    sed -e "s~%%MANGO_URL%%~${INSTANCE_URL}~g" | \
    sed -e "s~%%SELENIUM_HOST%%~${SELENIUM_HOST}~g" | \
    sed -e "s~%%IDP_SERVICE_URL%%~${IDP_SERVICE_URL}~g" | \
    sed -e "s~%%HYDRA_URL%%~${HYDRA_URL}~g" | \
    sed -e "s~%%IDM_NS%%~${IDM_NS}~g" | \
    sed -e "s~%%LDAP_HOST%%~${LDAP_SERVER_URL}~g" > ${BEHAT_SUITE}_behat.yml

../../vendor/bin/behat -s ${BEHAT_SUITE} --config ${BEHAT_SUITE}_behat.yml

testsResult=$?

rm ${BEHAT_SUITE}_behat.yml

if [ $testsResult != 0 ]; then
    exit $testsResult
fi
