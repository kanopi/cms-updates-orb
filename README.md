# Kanopi CMS Site Updater

The following orb was created as a way to do automated updated.

It will work with the following Content Management Systems (CMS).

- Drupal 7+
- WordPress

It will allow for updates using a composer method or using the respected 
CMS's tool (Drush/WPCLI).

For more information please reach out to one of the following people either in slack or email:

* Sean Dietrich <sean@kanopi.com>
* Paul Sheldrake <paul@kanopi.com>
* Jim Birch <jim@kanopi.com>

Additionally, there is the [#Orbs Channel](https://kanopi.slack.com/archives/CUBC4Q1B4) in the Kanopi
slack to reach out to.

## Usage

```yaml
version: 2.1
orbs:
  cms-updates: kanopi/cms-updates@x.y.z
workflows:
  updates:
    jobs:
      - cms-updates/run-update:
          cms: 'drupal'
          site-hosting: 'pantheon'
          site-id: 'abz123'
          site-env: 'live'
          update-method: 'composer'
```

The following parameters are also available for use within the workflow job.

Parameter | Type | Required | Default | Options | Description
----------|------|----------|---------|---------|-------------
php-version | enum | | 7.4 | 7.4, 8.0, 8.1| The PHP Version to use for the build.
cms | enum | X | | drupal, drupal7, wordpress | The CMS we should be running updates on.
repo | string | | `${CIRCLE_REPOSITORY_URL}` | | The url of the repository to clone.
pr-branch | string | | `${CIRCLE_BRANCH}` | | What branch should we be creating a Pull Request For.
docroot | string | | `.` | | The location where the projects document root is. Examples are web, docroot, http.
update-branch | string | | `automated/cms-updates` | | The name of the branch to run updates in.
site-hosting | enum | | `general` | general, pantheon, wpengine | The name of the hosting set up to use. This will provide the stacks for the project.
site-id | string | | | | The site name/id on the hosting provider to pull the database from.
site-env | string | | | | The environment on the hosting provider to pull the database from.
is-multisite | boolean | | false | | | Is this site a multi-site. Primarily used for regenerating the wp-config.php
multisite-subdomains | boolean | | false | | | Is this site a subdomain multi-site. Primarily used for regenerating the wp-config.php
db-type | enum | | `custom` | custom, drush, wpcli | The process used for pulling a database from the remote hosting provider. **NOTE** This is only used _IF_ the site-hosting is *NOT* one of the following acquia, pantheon, wpengine.
table-prefix | string | | `wp_` | | The table prefix to use. Primarily used for WordPress configuration.
update-method | enum | X | | composer, drush, wpcli | The process to pull updates for the cms..
update-message | string | | `Automated Update` | | The commit message used for any updates committed to the project. 
git-name | string | | `${GIT_NAME}` | | The name to commit items as.
git-email | string | | `${GIT_EMAIL}` | | The email to commit items as.
composer-version | enum | | `2` | 1, 2 | The version of composer to install and use.
cms-updates-config-repo | string | | `git@github.com:kanopi/cms-updates` | | The repo where configuration is stored for everything.
cms-updates-version | string | | `main` | | The branch/commit/tag to checkout and use for the configuration. This will changed based upon the version published.

### Hooks

While we try to account for most scenarios and have tried to make the following process as flexible as possible it won't 
always be 100%. For that we have built a hook system that allows the update process to be easily morphed to work with 
any project's needs.

Built into this update process is a set of hooks that can be executed. Before or after a particular step.

Noted below are the different events and what the Pre Hook and Post Hook files names are.

These files can be stored in the main repo of the project being updated within the `.updates` directory.

Event | Pre Hook File | Post Hook File
------|---------------|---------------
Checkout |  | post-checkout
Start | pre-start | post-start
Database Sync | pre-pull-db | post-pull-db 
Update | pre-update | post-update
Commit | pre-commit | post-commit
Create Pull Request | pre-create-pr | post-create-pr

These files should be executable. The easiest way is to run `chmod +x [filename]`.

**NOTE:** These files can be written in any language.

Start each file with the variation of the following.

```shell
#!/usr/bin/env bash
```

### Extras

It's entirely possible to have other things run after the update was successful. Examples are, having a slack message 
sent after the update happened. Please use the `post-steps` parameter on the job. This will help ensure any action 
happens after the update process.

## Examples

- Drupal 7
  - [Update with Drush on Pantheon](src/examples/run-update-pantheon-drupal7-drush.yml)
- Drupal 9+
  - [Update with Composer on Pantheon](src/examples/run-update-pantheon-drupal-composer.yml)
- WordPress
  - [Update with WPCLI on Pantheon](src/examples/run-update-pantheon-wordpress-wpcli.yml)
  - [Update with WPCLI on WPEngine](src/examples/run-update-wpengine-wordpress-wpcli.yml)
  - [Update with WPCLI on WPEngine (MultiSite)](src/examples/run-update-wpengine-wordpress-wpcli-multisite.yml)

## Testing

_TBD_

## Contributing

_TBD_