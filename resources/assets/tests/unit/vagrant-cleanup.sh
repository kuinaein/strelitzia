#!/usr/bin/env bash
set -eux
SCRIPT_DIR=$(cd $(dirname ${BASH_SOURCE:-$0}); pwd)
source $SCRIPT_DIR/vagrant-common.sh

curl -sS http://localhost:3000/cleanup

dive_to_vagrant_dir
vagrant halt
