set -eux
PB_REL="https://github.com/protocolbuffers/protobuf/releases"
PB_VERSION=26.1
TMP_FILE=/tmp/protoc.zip
BIN_FILE=/usr/local/bin/protoc
curl -sS -L --fail ${PB_REL}/download/v${PB_VERSION}/protoc-${PB_VERSION}-linux-x86_64.zip > $TMP_FILE
ls -lah /tmp
unzip -p $TMP_FILE bin/protoc > $BIN_FILE && chmod +x $BIN_FILE
rm -f
