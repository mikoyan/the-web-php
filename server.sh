#!/usr/bin/env sh

HOST=localhost
PORT=9000
ROOT=`dirname $0`

/usr/bin/env php -S $HOST":"$PORT -t $ROOT"/web" $ROOT"/web/dev_router.php"
