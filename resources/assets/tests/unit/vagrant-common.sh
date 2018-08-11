PROJECT_DIR=${SCRIPT_DIR%/resources/assets/tests/unit}
VAGRANT_DIR=$PROJECT_DIR/vagrant

function _at_finally {
  popd
}
function dive_to_vagrant_dir {
  pushd $VAGRANT_DIR
  trap _at_finally EXIT
}

function call_vagrant_server {
  browser=$1
  test_url=${2/localhost/10.0.2.2}
  test_url=$(node -p "encodeURIComponent(\"${test_url}\")")

  sleep 10
  curl -sS http://localhost:3000/run-tests/$browser?url=$test_url
  sleep 5
}
