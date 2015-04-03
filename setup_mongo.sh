#!/bin/bash

if [[ $TRAVIS_PHP_VERSION == "hhvm" ]]; then

    mkdir BUILD
    cd BUILD

    git clone git://github.com/facebook/hhvm.git
    cd hhvm
    export HPHP_HOME=`pwd`
    git checkout 1da451b # Tag:3.0.1
    cd ..

    git clone https://github.com/mongofill/mongofill-hhvm
    cd mongofill-hhvm
    /bin/bash tools/travis.sh

    which php
    which hhvm
    cat /etc/hhvm/php.ini

    # show mongo PHP extension version

    cd ../..

    phpenv rehash

    echo "ext-mongo version: `hhvm --php -r 'echo phpversion(\"mongo\");'`"
else
    yes '' | pecl install -f mongo-${MONGO_VERSION}

    phpenv rehash

    echo "ext-mongo version: `php -r 'echo phpversion(\"mongo\");'`"
fi

