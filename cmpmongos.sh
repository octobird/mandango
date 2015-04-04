#!/bin/bash

sudo php5enmod -s ALL mongo

phpunit 2>&1 > phpunit-mongo.txt

sudo php5dismod -s ALL mongo

phpunit 2>&1 > phpunit-mongofill.txt

#diff phpunit-mongo.txt phpunit-mongofill.txt
