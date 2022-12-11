#!/bin/sh
## Setup services
echo "Running tests"
docker exec my_theresa_apache php artisan test tests/Src/BoundedContext/

