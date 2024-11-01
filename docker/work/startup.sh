#!/usr/bin/env bash

set -e
set -u
set -o pipefail

artisan() {
    su -c "php artisan $*" -s /bin/bash devilbox
}
