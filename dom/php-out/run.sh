#!/bin/bash

for file in *.php; do
    echo "--- $file ---"
    /run/media/niels/MoreData/php-src/sapi/cli/php "$file"
done
