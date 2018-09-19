# Running Jenkins Pipeline

## General

Main benefit of Jenkinsfile-style of continuous integration is that developers have almost full control on what happens
in the test scenario and are free to create nearly any imagined continuous integration flow.

Another benefit of Jenkinsfile is the ability to rapidly develop the aforementioned flows. All you need to do is:
* Create a pull request with some Jenkinsfile.
* Observe its run on Jenkins server.
* Adjust desired parts of it locally.
* Commit and push it to github again (possibly with `git commit --amend`).
* Repeat N times until success.


In order to run Identity Provider CI on Jenkins Pipeline be sure you have:
* IdentityProvider repository (obvious one)
* `Jenkinsfile` in the root of the repository
* Dockerfiles to build required deployment images (resided mainly in tests directory)
* Kubernetes deployment-, pod-, service- files to create required environments inside k8s that
is run on Jenkins(see k8s directory)


## Flow
Current implementation of continuous integration for IdentityProvider implies the following steps.
* Part 1: Test IdentityProvider source code.
    * Fetch IdentityProvider repo with the changes of the PR.
    * Dockerize source code for different PHP versions and configurations(`7.1`, et al.).
    Corresponding Dockerfiles are located in the repo's root (e.g. [`Dockerfile.php71`](../../Dockerfile.php71))
    * Run code standards check in one of the IdentityProvider containers.
    * Run [Unit](../../tests/Unit) tests in all IdentityProvider containers.
    * Run [Functional](../../tests/Functional) tests in all IdentityProvider containers.
* Part 2: Test integration of IdentityProvider with SugarCRM
    * Fetch the latest Mango build (from branch suited to work with IdentityProvider)
    * Install SugarCRM from the fetched build and dockerize it. (scripts located [here](../../tests/docker/bootstrap/mango))
    * Build custom SAML server images ([base](../../tests/docker/saml), [augmented](../../tests/docker/saml-test))
    * Build custom LDAP image ([here](../../tests/docker/openldap))
    * Deploy SugarCRM environment, Selenium, SAML servers, LDAP servers inside inner k8s ([see current directory](.))
    * Run [Behat](../../tests/behat) tests to check Mango - IdentityProvider integration. Each suite (Local, LDAP, SAML)
    is executed in parallel to gain maximum performance.
 

## Tests

### Behat

#### Local
Local Behat tests suite is run to assure local auth for Mango works fine.
All the tests are run on simple installed SugarCRM instance without additional configuration.
The only thing to bear in mind is `$sugar_config['verify_client_ip'] = false;` that allows to neglect that 
test may be run from different machines inside k8s cluster.

#### LDAP
LDAP Behat tests suite is run to assure LDAP auth for Mango works correctly.
We use manually tweaked LDAP server that is deployed inside kubernetes.

#### SAML
SAML Behat tests suite is run to assure SAML auth for Mango works correctly.
We use manually tweaked SimpleSAML as a SAML2.0 reference server to test sanity of all SAML providers supported by
IdentityProvider and Mango.

Since plenty of configuration options are available for SAML auth within Mango, we spawn a bunch of SimpleSAML and
SugarCRM containers with configurations corresponding to each other in order to cover broader range of scenarios.
Configuration of containers is done seamlessly for user at the container run stage, so no additional tweaks are needed
when CI is triggered. The only thing you need to do is to maintain these [settings](../../tests/docker/saml-test/config)
and [these](mango/config) ones.

## Mango build
All the Behat tests are running on installed Mango build. Pipeline has a dedicated step "Fetch Mango build"
for fetching Mango build from the build system. The precedence of fetching the particular artifact depends on the pull
request and the branch against which it's done:
* For PRs against master:
    - Try to fetch dev build of the same author and branch as a current PR to IdentityProvider.
    - Try to fetch team dev build. Usually it's a single build that team creates for some feature.
    - Try to fetch the latest master Mango build.
* For PRs against any branch rather than master:
    - Try to fetch dev build of the same author and branch as a current PR to IdentityProvider.
    - Try to fetch the latest master Mango build.

## Caveats

### Images pulling
* Don't forget that in order to preserve lower build-time and decrease network traffic
we use `imagePullPolicy: IfNotPresent` in kubernetes deployment files.
So nevertheless build of images is done automatically the latest build present on Jenkins **may**
be not the one that contains the latest changes. To avoid this situation you can do either:
    - temporarily change `imagePullPolicy: IfNotPresent` to `imagePullPolicy: Always` for a desired image;
    - preventively push a desired image to registry with minikube build [commands](../minikube/README.md).

### Shared environment
Pull requests CI is being executed concurrently, so when there are several PRs some resources may be
shared between them.
* Workspaces (current directory of CI run) are not shared, so do not have to worry.
* Docker networks/images/containers are shared. Stick to PR-number and/or git commit hash suffixes when
creating one of them
* Kubernetes objects are shareable. The same recommendation.

### Jenkins/Pipeline bugs
Though Jenkins and Pipelines is a stable platform you may run in some unexpected failures.
The situation my be different at the time you read this doc, but nevertheless, please, consider:
* Sometimes k8s DNS fails to resolve commonly accessible URLs while building images and fetching code dependencies.
All you have to do is to relaunch CI (possibly by just `git commit --amend`)
* Each step is executed in a particular k8s node inside Jenkins, so there may occur situations when Jenkins CI run
looses the connection to the current node ("Pipe is not connected..." message denotes such a situation).
All you have to do is to relaunch CI once again.
Another helpful trick is to divide the whole pipeline into smaller bunches that are executed on
the same node, but not all at oce. This allows Jenkins to run smaller set of steps, then loose the node, and then again
proceed to execution, but in a different subset of steps.
* Don't use Pipeline's DSL `sleep` function - it causes Jenkins to loose current node. Instead use sleep in bash.
