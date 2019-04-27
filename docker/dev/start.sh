#!/usr/bin/env bash

# Stop previous containers
echo -n "Stop previous containers..."
echo -en '\n'
docker-compose stop

echo -n "Set permissions to 'uploads' directory..."
echo -en '\n'

UPLOAD_DIRECTORY=./../../public/uploads
MEDIA_DIRECTORY=./../../public/media

if [[ ! -d ${UPLOAD_DIRECTORY} ]]; then
  mkdir -p ${UPLOAD_DIRECTORY};
fi
chmod 777 -R ${UPLOAD_DIRECTORY}

if [[ ! -d ${MEDIA_DIRECTORY} ]]; then
  mkdir -p ${MEDIA_DIRECTORY};
fi
chmod 777 -R ${MEDIA_DIRECTORY}


# Up docker compose
echo -n "Up docker compose..."
echo -en '\n'
docker-compose up -d

# Install composer dependencies
echo -en '\n'
echo -n "Install compose dependencies..."
echo -en '\n'
docker exec php-fpm-container-dev composer install

# Run database migrations
echo -en '\n'
echo -n "Run database migrations..."
echo -en '\n'
until docker exec php-fpm-container-dev php bin/console doctrine:migrations:migrate --env=dev --no-interaction
do
    echo -en '\n'
    echo -n "Waiting postgres..."
    echo -en '\n'

    sleep 10

    echo -en '\n'
    echo -n "And try again to run migrations..."
    echo -en '\n'
done
