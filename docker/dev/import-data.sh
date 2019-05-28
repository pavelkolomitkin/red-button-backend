#!/usr/bin/env bash

echo -n "Importing regions..."
echo -en '\n'
docker exec -i postgres-db-container-dev psql -U postgres red-button < ./../common/data/regions.pgsql

echo -n "Importing companies..."
echo -en '\n'
docker exec -i postgres-db-container-dev psql -U postgres red-button < ./../common/data/companies.pgsql