<?php
    require 'vendor/autoload.php';
    use Elasticsearch\ClientBuilder;
    require_once 'functions.php';
    $client = ClientBuilder::create()->build();

    /*$profiles = json_decode(file_get_contents('data.json'), true);
    deleteIndex($client);
    createIndex($client);
    indexAllDocuments($client, $profiles);*/



    $fields = ['size', 'color', 'favoriteFruit'];

    $elements = [];
    foreach ($fields as $field) {
        $elements[] = "doc['{$field}'].value";
    }

    $delimiter = ' | ';
    $script = 'return '.implode(" + '$delimiter' + ", $elements).';';

    $result = $client->search([
        'index' => 'profile_index',
        'body' => [
            'size' => 0,
            'aggs' => [
                'duplicates' => [
                    'terms' => [
                        'script' => $script,
                        'size' => 1000,
                        'min_doc_count' => 2,
                    ],
                    'aggs' => [
                        'profiles' => [
                            'top_hits' => [
                                'size' => 100,
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]);

    foreach ($result['aggregations']['duplicates']['buckets'] as $bucket) {
        $fieldValues = explode($delimiter, $bucket['key']);
        $fieldValues = array_combine($fields, $fieldValues);
        $duplicatesCount = $bucket['doc_count'];
        print_r($fieldValues);

        $secondaryProfiles = $bucket['profiles']['hits']['hits'];
        $primaryProfile = array_shift($secondaryProfiles);
        foreach ($secondaryProfiles as $secondaryProfile) {
            print_r($secondaryProfile['_source']);
        }
    }
die;
    /*$result = $client->search([
        'index' => 'profile_index',
        'body' => [
            'aggs' => [
                'size_count' => [
                    'terms' => [
                        'field' => 'size',
                    ],
                    'aggs' => [
                        'color_count' => [
                            'terms' => [
                                'field' => 'color',
                            ],
                            'aggs' => [
                                'favorite_count' => [
                                    'terms' => [
                                        'field' => 'favoriteFruit',
                                    ],
                                ]
                            ]
                        ],

                    ]
                ]
            ]
        ]

    ]);*/


    dd($result);


