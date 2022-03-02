<?php

return [
    'target_php_version'              => '7.3',
    'minimum_target_php_version'      => '5.6',
    'directory_list'                  => [
        'esp_ent/templates_eparapheur',
    ],
    'exclude_file_regex'              => '@^vendor/.*/(tests?|Tests?)/@',
    'exclude_analysis_directory_list' => [
        'vendor/',
        'stofile',
        'stofile_certificats',
        'ci',
    ],
];
