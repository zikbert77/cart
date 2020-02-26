#!/usr/bin/env bash

docker-compose kill
docker-compose -f ./docker-compose.yml up -d --build
