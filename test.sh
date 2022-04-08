#!/bin/bash

DIR_TESTS='./tests'
PHP_UNIT='./vendor/bin/phpunit'

echo 'List tests'
$PHP_UNIT --bootstrap tests/bootstrap.php $DIR_TESTS --list-tests

echo

echo 'Execute tests'
$PHP_UNIT --bootstrap tests/bootstrap.php $DIR_TESTS --verbose