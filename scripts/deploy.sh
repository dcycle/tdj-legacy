#!/bin/bash
#
# Local version of D6 Tdj.
#
set -e

docker pull dcycle/drupal:6

echo ''
echo '-----'
echo 'About to create the legacy_tdj_default network if it does'
echo 'exist, because we need it to have a predictable name when we try to'
echo 'connect other containers to it (for example browser testers).'
echo 'The network is then referenced in docker-compose.yml.'
echo 'See https://github.com/docker/compose/issues/3736.'
docker network ls | grep legacy_tdj_default || docker network create legacy_tdj_default

echo ''
echo '-----'
echo 'About to start persistent (-d) containers based on the images defined'
echo 'in ./Dockerfile and ./docker-compose.yml. We are also telling'
echo 'docker-compose to rebuild the images if they are out of date.'
docker-compose up -d --build

echo ''
echo '-----'
echo 'Running the deploy script on the running containers. This installs'
echo 'Drupal if it is not yet installed.'
docker-compose exec drupal /scripts/deploy.sh

# If you need to do stuff after deployment such as set a state variable, do it
# here.

echo ''
echo '-----'
echo 'Running the update script on the container.'
docker-compose exec drupal /scripts/update.sh

echo ''
echo '-----'
echo ''
echo 'If all went well you can now access your site at:'
./scripts/uli.sh
echo '-----'
echo ''
echo 'You might want to visit /admin/reports/status and fix any problems.'
echo ''
