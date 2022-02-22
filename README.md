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

<!-- Parameter Table Start -->

Parameter | Type | Required | Default | Options | Description
----------|------|----------|---------|---------|-------------
cms | enum | X |  | drupal, drupal7, wordpress | Type of CMS to run updates on.
cms-updates-config-repo | string |  | git@github.com:kanopi/cms-updates | drupal, drupal7, wordpress | The repo to pull down from the configuration.
cms-updates-version | string |  | main | drupal, drupal7, wordpress | Version of CMS Update Script to download.
composer-version | enum |  | 2 | 1, 2 | Version of composer to use. Default 2.x
db-type | enum |  | custom | custom, drush, wpcli | What is the method for pulling the database.
docroot | string |  | . | custom, drush, wpcli | Where is the DOCROOT of the project?
exclude-pr | boolean |  | false | custom, drush, wpcli | Exclude PR from the Process
git-email | string |  | ${GIT_EMAIL} | custom, drush, wpcli | The email to use for commits
git-name | string |  | ${GIT_NAME} | custom, drush, wpcli | The name to use for commits
is-multisite | boolean |  | false | custom, drush, wpcli | Is this site a multi-site.
multisite-subdomains | boolean |  | false | custom, drush, wpcli | Is the WordPress multisite a subdomains multisite?
php-version | enum |  | 7.4 | 7.4, 8.0, 8.1 | Tag used for PHP version. Image: cimg/php
pr-branch | string |  | ${CIRCLE_BRANCH} | 7.4, 8.0, 8.1 | What is the main branch of the project that should be used.
repo | string |  | ${CIRCLE_REPOSITORY_URL} | 7.4, 8.0, 8.1 | The url to use for cloning the repo
run-local | boolean |  | false | 7.4, 8.0, 8.1 | 
site-env | string |  |  | 7.4, 8.0, 8.1 | The environment on the remote host to pull information from.
site-hosting | enum |  | general | general, pantheon, wpengine | What hosting is the site using?
site-id | string |  |  | general, pantheon, wpengine | The site name on the remote host to pull information from
table-prefix | string |  | wp_ | general, pantheon, wpengine | The table prefix to use. Primarily used for WordPress configuration.
update-branch | string |  | automated/cms-updates | general, pantheon, wpengine | The name of the branch to run updates with.
update-message | string |  | Automated Updated | general, pantheon, wpengine | Commit message used for changed items.
update-method | enum | X |  | composer, drush, wpcli | The update-method used for running updates.

<!-- Parameter Table End -->

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

### Linting

The project uses a set of linting standards primarily for the YAML files. The configuration standards are found within
the `.yamllint` file in the project root.

To run the standards from the project directory run the `./bin/lint` command. This will output any possible issues.

Example

```shell
$ ./bin/lint
/project/src/commands/run-update.yml
  132:23    error    no new line character at the end of file  (new-line-at-end-of-file)

```

### Validating

One additional process that should be done when testing is confirming that the Orb will validate. This means checking
to see if we are using the proper parameters or the proper placeholders. We are including every required parameter
when it is needed.

To run the validation from the project directory run the `.bin/validate` command. This will output any possible issues.

Example

```shell
$ ./bin/validate
Orb at `/project/validate.yml` is valid.
```

### Running Build Test

_TBD_

## Contributing

_TBD_