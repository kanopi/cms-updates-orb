# Kanopi CMS Site Updater

MORE TBD

## Testing

* Install CircleCi CLI
* `cp ./tests/.env.sample ./tests/.env`
* Copy Fill In Keys in `.env` file
* Run `./tests/run-test [job-id]`

## Examples

```yaml

jobs:
  coit-8:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "composer"
          cms: "drupal9"
          repo: "git@github.com:kanopi/coit-8"
          pr-branch: "master"
          db-type: "pantheon"
          docroot: "web"
          site-hosting: "pantheon"
          site-id: "coit-main"
          site-env: "live"
  coit-spot-removal:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "drush"
          cms: "drupal7"
          repo: "git@github.com:kanopi/coit-spot-removal"
          pr-branch: "master"
          db-type: "pantheon"
          docroot: "."
          site-hosting: "pantheon"
          site-id: "coit-spot-removal"
          site-env: "live"
  mises:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "drush"
          cms: "drupal7"
          repo: "git@github.com:kanopi/mises"
          pr-branch: "main"
          db-type: "pantheon"
          docroot: "."
          site-hosting: "pantheon"
          site-id: "mises"
          site-env: "live"
  mises-api:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "composer"
          cms: "drupal9"
          repo: "git@github.com:kanopi/mises-api"
          pr-branch: "main"
          db-type: "pantheon"
          docroot: "web"
          site-hosting: "pantheon"
          site-id: "mises-api"
          site-env: "dev"
  kanopi-2019:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "composer"
          cms: "wordpress"
          repo: "git@github.com:kanopi/kanopi-2019"
          pr-branch: "master"
          db-type: "pantheon"
          docroot: "web"
          site-hosting: "pantheon"
          site-id: "kanopi-2019"
          site-env: "live"
          composer-version: '1'
  diebenkorn:
    executor: "custom/pantheon"
    steps:
      - custom/run-updates:
          method: "wpcli"
          cms: "wordpress"
          repo: "git@github.com:kanopi/diebenkorn"
          pr-branch: "main"
          db-type: "pantheon"
          docroot: "."
          site-hosting: "pantheon"
          site-id: "diebenkorn"
          site-env: "live"
```