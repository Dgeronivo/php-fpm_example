#!/bin/bash

[ ! -f .env.local ] && cp .env .env.local

docker-compose down
docker-compose up -d --build
