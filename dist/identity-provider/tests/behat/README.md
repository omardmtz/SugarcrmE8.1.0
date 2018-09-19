# End-to-end testing with [Behat](http://behat.org/)

# Running in minikube

The recommended way is to run Behat tests in minikube. See [here](../../k8s/minikube/README.md).

# Running on local machine only

## Setting up
For setting up and running Behat:
 * Copy file `behat.yml.template` to `behat.yml`
 * Fill base_url in `behat.yml`
 * Fill other wildcard URLs with appropriate values (e.g. %%LDAP_HOST%% -> http://my-local-ldap.host/)
 * Run
 ```sh
  cd sugarcrm
  chmod +x behat.sh
  ./behat.sh -d
```
*-d* flag automatically downloads Selenium driver (is done on the first launch).

## Configuration
In `behat.yml` change base URL for SugarCRM instance `base_url: http://sugar.host/` 

## Installation
Download: 
 * [chromedriver](https://sites.google.com/a/chromium.org/chromedriver/downloads)
 * [Selenium Standalone Server](http://www.seleniumhq.org/download/)

Run:
 * chromedriver - `unzip chromedriver_mac64.zip && ./chromedriver`
 * Selenium Standalone Server - `java -jar selenium-server-standalone-3.3.1.jar`

## Running tests:
`./vendor/bin/behat`
