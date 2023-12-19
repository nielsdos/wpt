#!/bin/bash

for file in *.php; do
    /run/media/niels/MoreData/php-src/sapi/cli/php "$file"
done
