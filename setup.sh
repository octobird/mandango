#!/bin/bash

#
# Setup script for Travis CI server & local instalation of dependencies
# for running phpunit tests.
#

function die {
    echo $1
    exit 1
}

echo "Checking for composer.phar..."

if [[ -e composer.phar ]]; then
    echo "...found, skipping installation."
else
    echo "...not found, installing..."
    php -r "readfile('https://getcomposer.org/installer');" | php || \
        die "...failed to install composer.phar, aborting."
    echo "...done."
fi

echo "Checking for composer.lock..."

if [[ -e composer.lock ]]; then
    echo "...found, using packages defined in the lock file."
else
    echo "...no lock file was found, running update..."
    ./composer.phar update || \
        die "...failed to update dependencies, aboring."
    echo "...done."
fi

echo "Installing composer-defined components..."
./composer.phar install || \
    die "...failed to install composer components, aborintg."
echo "...done."

