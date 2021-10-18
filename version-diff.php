#!/usr/bin/php
<?php


function check_file_exists($file_name) {
    if (!file_exists($file_name)) {
        die(
            sprintf("File %s not available", $file_name)
        );
    }
}

function build_version_table($info) {
    $str = '';

    if (sizeof($info) > 0) {
        $headers = ['Package', 'Old Version', 'New Version', 'Status', 'Description'];
        $headers_seperator = $headers;
        array_walk($headers_seperator, function(&$header) {
            $header = str_pad('', strlen($header), '-');
        });
        $str .= implode(' | ', $headers) . PHP_EOL;
        $str .= implode('-|-', $headers_seperator) . PHP_EOL;

        foreach ($info AS $package) {
            $str .= implode(" | ", [
                $package['name'],
                $package['old_version'] ?? '',
                $package['new_version'] ?? '',
                $package['status'] ?? '',
                $package['description'] ?? '',
            ]) . PHP_EOL;
        }
    }

    return $str;
}

$args = $argv;

array_shift($args);

$type = array_shift($args);

$types = ["wordpress", "drush", "composer"];

if (!in_array($type, $types)) {
    die(
        sprintf('Type %s not supported', $type)
    );
}

$output_file_name = array_shift($args);

$data = [];

$old_file = array_shift($args);
check_file_exists($old_file);
$old_file_data = json_decode(file_get_contents($old_file), true);

$new_file = array_shift($args);
check_file_exists($new_file);
$new_file_data = json_decode(file_get_contents($new_file), true);

$versions = [];

$title = '';

$description = '';

switch($type) {
    case 'wordpress':
        break;

    case 'composer':
        $title = '# Composer Package Updates';
        $description = 'The following packages have changed in some way. Please note the changes.';

        foreach ($old_file_data['installed'] AS $package) {
            if (!isset($versions[$package['name']])){
                $package['old_version'] = $package['version'];
                unset($package['version']);
                $package['status'] = 'Removed';
                $versions[$package['name']] = $package;
            }
        }

        foreach ($new_file_data['installed'] AS $package) {
            if (!isset($versions[$package['name']])){
                $package['new_version'] = $package['version'];
                unset($package['version']);
                $package['status'] = 'Installed';
                $versions[$package['name']] = $package;
            } else {
                $versions[$package['name']]['new_version'] = $package['version'];

                if ($versions[$package['name']]['new_version'] == $versions[$package['name']]['old_version']) {
                    $versions[$package['name']]['status'] = 'Same';
                } elseif (empty($versions[$package['name']]['new_version'])) {
                    $versions[$package['name']]['status'] = 'Removed';
                } elseif ($versions[$package['name']]['new_version'] != $versions[$package['name']]['old_version']) {
                    $versions[$package['name']]['status'] = 'Updated';
                }
            }
        }

        break;

    case 'drush':
        break;
}

$versions = array_filter($versions, function($package)  {
    return !($package['status'] == 'Same');
});

$version_table = $title . PHP_EOL . PHP_EOL . ($description != "" ? $description . PHP_EOL . PHP_EOL : '') . build_version_table($versions);

$file_put_flags = 0;

if (file_exists($output_file_name)) {
    $file_put_flags = FILE_APPEND;

    $version_table = PHP_EOL . '-----------------------------------------------------------' . PHP_EOL . PHP_EOL . $version_table;
}

file_put_contents($output_file_name, $version_table, $file_put_flags);