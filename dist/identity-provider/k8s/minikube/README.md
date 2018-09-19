# Running Tests in Minikube


## General

The main reason for running tests in minikube is to:
* eliminate set-up and deploying efforts
* be as close as possible to the real test environment of Jenkins Pipeline.


## Prerequisites

* You have at least 2 Gb of RAM free to run a virtual machine.
* Virtualbox installed. (Minikube can work with other virtualization engines as well, not tested though).
* [minikube](https://github.com/kubernetes/minikube) installed on your local machine.
* [kubectl](https://kubernetes.io/docs/user-guide/kubectl-overview/) utility installed on your local machine.
* [Docker](https://www.docker.com) installed on your local machine. 
* The latest version of IdentityProvider repository.
* The latest **zip** build of SugarCRM from branch that works with IdentityProvider.
* VNC client on your local machine.


## How to run Unit and Functional tests
1. `cd IdentityProvider`
1. `./ci.sh`
1. `./vendor/bin/phpunit`

You may want to do that within dockerized IdentityProvider. If so, read section below.


## How to run Behat tests

1. Within any directory run `minikube start`. It may take a while on the first launch.

1. `cd IdentityProvider/tests/docker/bootstrap`

1. Build required images: SugarCRM, LDAP server, SAML server.
Run `./build-docker-minikube.sh` with appropriate flags:
    * **-t** - path to Sugar zip archive.
    * **-k** - Sugar license key.
    * **--update-saml-registry** - whether you want to re-build SAML server image. By default the latest one
  will be pulled from Sugar registry.
    * **--update-ldap-registry** - whether you want to re-build LDAP server image. By default the latest one
  will be pulled from Sugar registry.

1. `cd IdentityProvider/k8s/minikube`

1. Run `./deploy.sh` with appropriate flags:
    * **-f** - path to IdentityProvider folder mapped inside minikube (ex. */Users/username/work/IdentityProvider*).

This will roll out all the needed kubernetes namespaces, deployments, pods and services inside minikube.

1. Run `kubectl --namespace idm-ns get pods` and `kubectl --namespace idm-ns get deployments` to be sure all pods and
deployments are running.

    **READY** column should be like *1/1*, **STATUS** column should be *Running*.
    
    | READY         | STATUS        |
    | ------------- |:-------------:|
    | 1/1           | Running       |
    
    Statuses other then that usually mean docker images were either not found 
    or are currently in the process of pull/deployment.

1. Additionally you may want to see your services and/or obtain their DNS names inside kubernetes cloud.
You can do that by running `kubectl --namespace idm-ns get services`

1. Connect to deployment with Selenium and Chrome via VNC:
    * In the second terminal tab run
`kubectl --namespace idm-ns port-forward $(kubectl --namespace idm-ns get pods --selector=app=selenium --output=name | cut -d'/' -f 2) 5900:5900`
    * Run `open vnc://127.0.0.1:5900` (command for macOS. Other OS should behave similarly).
    * In VNC window enter password "secret" ([see](https://github.com/SeleniumHQ/docker-selenium#debugging))
Now you should see VNC window with Linux running Chrome browser. Here Behat tests will be physically launched. 

1. Given kubernetes objects are up and running you can run one of the Behat suites:
    * *Local* - "default"
    * *LDAP* - "ldap"
    * *SAML* - "saml"

    To do that run 
    `kubectl --namespace idm-ns exec -it idm -- tests/behat/behat.sh -u ${mangoUrl} -s ${suite} -e selenium -l ldap -n idm-ns`
    
    If you see an error message similar to `tests/behat/behat.sh: line 123: ../../vendor/bin/behat: No such file or directory`,
    it means that you do not have installed vendors in `idm` image (and hence locally).
    To solve this issue simply run: `kubectl --namespace idm-ns exec -it idm -- composer install`

    Where:
    * `${mangoUrl}` is URL of the container with installed SugarCRM
    * `${suite}` is Behat suite name you want to run
    
    What is tricky here is that each Mango container has specific settings appropriate only for particular Behat suite,
    or even for particular feature inside the suite, so running suites with inappropriate `${mangoUrl}`
    will result is error.
    
    That said, here is the list of possible Mango containers and their description:
     * *http://behat-tests-mango* - mango for local auth suite. A base one.
     * *http://behat-tests-mango* - mango for LDAP auth
     * *http://behat-tests-mango-saml-base* - mango for SAML auth

1. Observe how tests are run in terminal and VNC window.

    If SugarCRM occurs to be not installed it can be caused by enabled ingress addon.
    To solve this temporarily disable it by running `minikube addons disable ingress`. And go back to step #3.

1. When you are done simply run `./undeploy.sh`. This will delete all previously created kubernetes objects.


## Tests development
The **idm** pod running inside minikube has mappings from your IdentityProvider source files to /var/www/html, so
in order to make corrections to Behat tests and then check them in minikube all you have to do is:
1. Make changes to IdentityProvider source files on your local machine.
1. Re-run tests.

## Allow minikube resolve host machine hosts
```bash
VBoxManage modifyvm "minikube" --natdnshostresolver1 on
```