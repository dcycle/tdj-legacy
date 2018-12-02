#!/bin/bash
#
# Update an environment.
#
# This can be called when updating Acquia, or on local Docker environments
# from ./scripts/deploy.sh, in which case there are no arguments.
#
set -e

echo 'Running drush rr'
drush rr
echo 'Running updb'
drush updb -y
