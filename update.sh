#!/usr/bin/env sh

ROOT=`dirname $0`

/usr/bin/env php $ROOT"/app/console.php" ingest:update
