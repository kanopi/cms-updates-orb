#!/usr/bin/env bash

#: exec_target = cli

## Refresh the local setup with the database
##
## Usage: fin refresh
##                --site [site]    (Site ID on Acquia)                [required]
##                --env [env]      (Site Env on Acquia)               [default: dev]
##                --db [database]  (Database name to pull)            [default: all]
##                --live           (Pull a live database)             [default: false]
##                --data [data]    (Data to pull db/files/all)        [default: db]
##                --wait [wait]    (How much time to wait in seconds) [default: 5]
##                --rsync [rsync]  (Rsync Options)                    [default: -avz]
##

set -e

( [[ "${ACQUIA_CLI_KEY}" == "" ]] || [[ "${ACQUIA_CLI_SECRET}" == "" ]] ) &&
  echo "ACQUIA_CLI_KEY and ACQUIA_CLI_SECRET variables required." &&
  exit 1

# Default Variables
LIVE=false
SLEEP_TIME=5
ACQUIA_ENV=${ACQUIA_ENV:-dev}

[[ "${ACQUIA_SITE}" == "" ]] && echo "ACQUIA_SITE required" && exit 1;

echo "Pulling Site: ${ACQUIA_SITE}..."

# Create Local Database
create_db () {
  local DB=$1
  local DOWNLOAD_FILE=$2
  [[ "${DB}" == "" ]] || [[ "${DOWNLOAD_FILE}" == "" ]] || [[ ! -f ${DOWNLOAD_FILE} ]] && echo "Database name required and or Downloaded file does not exist..." && exit 1;
  mysql -u root -p${DB_ROOT_PASSWORD} -h ${DB_HOST} -e "DROP DATABASE IF EXISTS ${DB};"
  mysql -u root -p${DB_ROOT_PASSWORD} -h ${DB_HOST} -e "CREATE DATABASE IF NOT EXISTS \`${DB}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
  mysql -u root -p${DB_ROOT_PASSWORD} -h ${DB_HOST} -e "GRANT ALL PRIVILEGES ON \`${DB}\`.* TO \`${DB_USER}\`";
  zcat < ${DOWNLOAD_FILE} | mysql -u ${DB_USER} -p${MYSQL_PASSWORD} -h ${MYSQL_HOST} ${DB}
}

# Pull Database from Acquia...
pull_db () {
  local AC_DB=$1
  DOWNLOAD_FILE=/tmp/${ACQUIA_SITE}.${ACQUIA_ENV}.${AC_DB}.sql.gz

  [[ "${AC_DB}" == "" ]] && return;

  echo "Getting Last Backup..."
  DOWNLOAD_ID=$(acli --no-interaction api:environments:database-backup-list --limit 1 -- ${ACQUIA_SITE}.${ACQUIA_ENV} ${AC_DB} | jq .[].id -r)

  if [[ "${DOWNLOAD_ID}" == "" ]]; then
    echo "Creating Backup..."
    NOTIFICATION_ID=$(acli --no-interaction api:environments:database-backup-create ${ACQUIA_SITE}.${ACQUIA_ENV} ${AC_DB} | jq -r ._links.notification.href | sed 's#https://cloud.acquia.com/api/notifications/##')
    STATUS=$(acli --no-interaction api:notifications:find ${NOTIFICATION_ID} | jq -r .status)
    while [[ "${STATUS}" != "completed" ]]; do
      sleep ${SLEEP_TIME}
      echo "Checking Backup Status..."
      STATUS=$(acli --no-interaction api:notifications:find ${NOTIFICATION_ID} | jq -r .status)
    done

    DOWNLOAD_ID=$(acli --no-interaction api:environments:database-backup-list --limit 1 -- ${ACQUIA_SITE}.${ACQUIA_ENV} ${AC_DB} | jq .[].id -r)
  fi

  if [[ "${DOWNLOAD_ID}" == "" ]]; then
      echo "Issue creating backup on Acquia..."
      exit 1
  fi

  echo "Getting Download Url..."
  DOWNLOAD_URL=$(acli --no-interaction api:environments:database-backup-download -- ${ACQUIA_SITE}.${ACQUIA_ENV} ${AC_DB} ${DOWNLOAD_ID} | jq .url -r)

  echo "Downloading Backup..."
  curl -fsSL -o ${DOWNLOAD_FILE} ${DOWNLOAD_URL}

  echo "Importing Database..."
  create_db $AC_DB $DOWNLOAD_FILE
}

echo "Running Database Sync..."
for DB in $(acli --no-interaction api:environments:database-list ${ACQUIA_SITE}.${ACQUIA_ENV} | jq -r .[].name)
do
  echo "Starting on database ${DB}..."
  pull_db $DB
done
