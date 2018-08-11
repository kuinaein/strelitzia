#!/usr/bin/env bash
set -eu
SCRIPT_DIR=$(cd $(dirname ${BASH_SOURCE:-$0}); pwd)
source $SCRIPT_DIR/vagrant-common.sh

dive_to_vagrant_dir
vagrant up 2>&1
call_vagrant_server edge $1
