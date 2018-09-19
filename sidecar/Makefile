SUGAR_ENV ?= dev
SUGAR_SIDECAR_DOCKER_VERSION ?= 6.10.3

# V := 1 # When V is set, print commands and build progress.
Q := $(if $V,,@)

.DEFAULT_GOAL := build

.PHONY: help
help:
	@echo "Example:"
	@echo " make clean build"
	@echo ""
	@echo "ENVIRONMENT VARS:"
	@echo " SUGAR_ENV=      set to production when building the release packages Default: $(SUGAR_ENV)"
	@echo ""
	@echo "TARGETS:"
	@echo " build:          builds sidecar"
	@echo " release_build:  alias to build"
	@echo " docker_build:   runs the build inside of docker instead of on the local host"
	@echo " clean:          resets the compile versions"

.PHONY: build
build:
	@echo "=====> Building Sidecar <====="
	$Q yarn install
	$Q node_modules/gulp/bin/gulp.js build
ifeq ($(SUGAR_ENV), production)
	@echo "=====> Removing Dev Dependencies <====="
	$Q yarn install --production --no-bin-links
endif

.PHONY: release_build
release_build: build

.PHONY: docker_build
docker_build:
	@echo "=====> Building In Docker <====="
	$Q docker run -v $(CURDIR):/sidecar -e SUGAR_ENV=$(SUGAR_ENV) -w /sidecar --rm node:$(SUGAR_SIDECAR_DOCKER_VERSION) make build

.PHONY: clean
clean:
	@echo "=====> Cleaning Minified Files <====="
	$Q git checkout -- minified/sidecar.min.js
	$Q git checkout -- minified/sidecar.min.js.map
