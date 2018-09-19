#!/bin/bash
cd /tmp
rm -rf /tmp/IdentityProvider
git clone git@github.com:sugarcrm/IdentityProvider.git
cd IdentityProvider

docker build --pull -t registry.sugarcrm.net/identity-provider/identity-provider:latest -f app/deploy/Dockerfile .

read -p "Push to sugar registry? [y/n]" yn
case $yn in
  [Yy]* ) docker push registry.sugarcrm.net/identity-provider/identity-provider:latest
;;
esac

read -p "Push to quay? [y/n]" yn
case $yn in
  [Yy]* )
echo $tag
     tag='manual-'`date -u +%Y%m%d%k%M`
     docker tag registry.sugarcrm.net/identity-provider/identity-provider:latest quay.io/sugarcrm/idm-login:$tag
     docker tag registry.sugarcrm.net/identity-provider/identity-provider:latest quay.io/sugarcrm/idm-login:latest-manual
     docker login quay.io
     docker push quay.io/sugarcrm/idm-login:$tag
     docker push quay.io/sugarcrm/idm-login:latest-manual
  ;;
esac

