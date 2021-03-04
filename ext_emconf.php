<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'File categories',
    'description' => 'Small TYPO3 extension to add the categories to the file metadata',
    'category' => 'fe',
    'author' => 'Jan Kiesewetter',
    'author_email' => 'jan@t3easy.de',
    'author_company' => 't3easy',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.10-8.7.99',
        ],
    ],
];
