#!/bin/bash

export WEBSERVER_MODE=artisan
docker network inspect appx >/dev/null 2>&1 || docker network create --driver bridge appx
docker buildx build --build-arg UID=$(id -u) --build-arg GID=$(id -g) --build-arg USER=${USER} -t appx -f .docker/install/Dockerfile .
docker compose build --build-arg UID=$(id -u) --build-arg GID=$(id -g) --build-arg USER=${USER}
