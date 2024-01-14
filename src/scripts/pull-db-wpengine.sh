#!/usr/bin/env bash

# Abort if anything fails
set -e

if [[ "${SITE_ID}" == "" ]]; then
  echo "SITE_ID variable is required"
  exit 1
fi

DB_FILE="${SITE_ID}.sql"
LOCAL_FILE="/tmp/${DB_FILE}"
SSH_URL="${SITE_ID}@${SITE_ID}.ssh.wpengine.net"

echo "Exporting the remote database..."
ssh ${SSH_URL} "cd /sites/${SITE_ID}; wp db export ${DB_FILE}"

echo "Downloading Database..."
rsync -azv ${SSH_URL}:/sites/${SITE_ID}/${DB_FILE} ${LOCAL_FILE}

echo "Database downloaded. Removing remote db file..."
ssh ${SSH_URL} "rm /sites/${SITE_ID}/${DB_FILE}"

echo "Importing Database..."
cat ${LOCAL_FILE} | pv --eta --progress --size `ls -l ${LOCAL_FILE} | awk '{print $5}'` --name '  Importing.. ' | mysql -h ${DB_HOST} -u ${DB_USER} -p${DB_PASS} ${DB_NAME}
