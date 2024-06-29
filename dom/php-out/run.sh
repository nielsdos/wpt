#!/bin/bash

for file in *.php; do
    echo "--- $file ---"
    /run/media/niels/MoreData/php-src-multitasking/sapi/cli/php "$file"
    #/home/niels/php-src/sapi/cli/php "$file"
done
