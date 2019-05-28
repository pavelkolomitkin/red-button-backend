#!/usr/bin/env bash

echo -n "Importing regions..."
echo -en '\n'
docker exec -i postgres-db-container-test psql -U postgres red-button-test < ./../common/data/regions.pgsql

echo -n "Importing companies..."
echo -en '\n'
docker exec -i postgres-db-container-test psql -U postgres red-button-test < ./../common/data/companies.pgsql