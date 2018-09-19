# Identity Provider

- [Installation](#installation)
    - [Environment](#environment)
    - [Application](#application)
    - [Logging](#logging)
- [Docker](#docker)
    - [Production](#production)
    - [Local deploy](#local-deploy)
    - [Jenkins and minikube](#jenkins-and-minikube)
- [Testing](#testing)
    - [General](#general)
    - [Continuous integration](#continuous-integration)
    - [Local test environment](#local-test-environment)


# Installation
---


### Environment
* PHP 7.1
* MySQL 5

The application requires minimal server config.

For Apache you need add:
> DirectoryIndex app.php

into *<Directory>* section.

For nginx:
>  try_files $uri /app.php;

into *location* section


### Application
* rename file */app/configs/paramaters.php.dist* -> *paramaters.php*
* enter valid parameters into *paramaters.php*
* run migrations command to create db schema. To do that you have to go in terminal to the root of application and run ./bin/console migrations:migrate
* run *fixtures:load* command. To do that you have to go in terminal to the root of application and run ./bin/console fixtures:load  This query creates 10 rows in user's table.
* you can test local authorization using following login / password pairs: user1 / user1pass, user2 / user2pass, ..., user10 / user10pass
* user3, user6 and user9 are marked as deleted so you cannot pass authorization using theirs credentials

### Logging

As far as [monolog](https://github.com/Seldaek/monolog) logger is used, this library implements the 
[PSR-3](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-3-logger-interface.md) interface.
By default [Identity Provider](#identity-provider) stores logs to `var/logs`. 
Also logs can be exported to external syslog server.
```php
$params['monolog']['monolog.handlers'] = [
    new \Monolog\Handler\SyslogUdpHandler('<syslog server>', <port>)
];
```


# Docker
---

#### Production

To get the latest image of **Identity Provider Service** run:

```bash
docker pull registry.sugarcrm.net/identity-provider/identity-provider:latest
```


To build a docker image of the **Identity Provider Service** locally from sources run:

```bash
cd path/to/this-repo
docker build -t registry.sugarcrm.net/identity-provider/identity-provider:latest -f app/deploy/Dockerfile .
```


To push the image to sugar registry run:

```bash
docker push registry.sugarcrm.net/identity-provider/identity-provider:latest
```

To push the image to quay.io for staging/production run:

```bash
docker login quay.io
docker push quay.io/sugarcrm/idm-login:manual-YYYYMMDDHHmm
```


To build and push images to both registries you can run script `deploy-images.sh`

#### Local deploy

To run fully dockerized Identity Provider Service on your local machine run:

- `cd IdentityProvider/app/deploy`
- `docker-compose -f local-compose.yml build`
- `docker-compose -f local-compose.yml up`
- `docker-compose -f local-compose.yml exec idp bash`
- `./bin/console migrations:migrate`
- `./bin/console fixtures:load`
- `... After you've done and do not need it anymore`
- `docker-compose -f local-compose.yml down`

#### Jenkins and minikube

Docker files like `Dockerfile.php71`, etc. are used mainly for testing in Jenkins Pipeline.
They are not responsible for creating and running Identity Provider as a service and are used for checking the sanity
of IdentityProvider as a library against different versions of PHP.

`Dockerfile.local` is used to simulate Jenkins Pipeline flow in minikube. It allows you to have an Identity Provider
image but with all its files mapped to your local ones so that you can rapidly change them and see
how it affects test runs.

# Testing
---

### General
To add/modify fixtures for functional/unit tests you should use test accounts for third-party
Identity Provider Services. Accounts' information can be found in internal doc
[here](https://docs.google.com/a/sugarcrm.com/document/d/1PySEsyqx4wji1RqQjm4J0gRYthaNA1AzY8s6XB-5yU4).

### Continuous integration
Identity Provider repository uses Jenkins Pipeline for continuous integration.
For additional information see corresponding [documentation](k8s/pipeline/README.md).

### Local test environment
All testing is recommended to perform inside minikube in order to:
* eliminate set-up and deploying efforts
* be as close as possible to the real test environment of Jenkins Pipeline

For additional information see corresponding [documentation](k8s/minikube/README.md).
