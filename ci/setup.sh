#!/bin/bash

#
# Setup script for Travis CI server & local instalation of dependencies
# for running phpunit tests.
#

function die {
    echo $1
    exit 1
}

echo "Checking mongodb driver extension version..."

if [[ $TRAVIS_PHP_VERSION == "hhvm" ]]; then
    echo "Installing mongodb-hhvm-driver..."

    mkdir BUILD
    cd BUILD

    # install libjemalloc
    #wget http://ubuntu.mirrors.tds.net/ubuntu/pool/universe/j/jemalloc/libjemalloc1_3.6.0-2_amd64.deb
    #sudo dpkg -i libjemalloc1_3.6.0-2_amd64.deb
    #rm libjemalloc1_3.6.0-2_amd64.deb
    #wget http://ubuntu.mirrors.tds.net/ubuntu/pool/universe/j/jemalloc/libjemalloc-dev_3.6.0-2_amd64.deb
    #sudo dpkg -i libjemalloc-dev_3.6.0-2_amd64.deb
    #rm libjemalloc-dev_3.6.0-2_amd64.deb
    #sudo apt-get install libdouble-conversion-dev libjemalloc-dev libtbb-dev

    # install mongodb-hhvm-driver
    git clone https://github.com/mongodb/mongo-hhvm-driver --branch 1.1.2
    cd mongo-hhvm-driver
    git submodule update --init
    hphpize
    cmake .
    make configlib
    make

    sudo mkdir /hhvm-extensions && sudo mv mongodb.so /hhvm-extensions

    sudo echo "hhvm.dynamic_extensions[mongodb]=/hhvm-extensions/mongodb.so" >> /etc/hhvm/php.ini

    cd ../..

    command -v phpenv 2>&1 > /dev/null && phpenv rehash

    echo "mongo-hhvm-driver: `hhvm --php -r 'echo phpversion(\"mongodb\");'`"
elif [[ $TRAVIS_PHP_VERSION == "5.5" ]] || [[ $TRAVIS_PHP_VERSION == "5.6" ]]; then
    echo "Installing mongodb-php-driver..."
    yes "" | pecl install mongodb
    command -v phpenv 2>&1 > /dev/null && phpenv rehash
    echo "mongo-php-driver version: `php -r 'echo phpversion(\"mongodb\");'`"
else
    echo "mongo-php-driver version: `php -r 'echo phpversion(\"mongodb\");'`"
fi

echo "Installing composer-defined components..."
./composer.phar install || \
    die "...failed to install composer components, aborintg."
echo "...done."
