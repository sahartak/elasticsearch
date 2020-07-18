<?php
    require 'vendor/autoload.php';
    use Elasticsearch\ClientBuilder;
    require_once 'functions.php';
    $client = ClientBuilder::create()->build();
    /*
    $profiles = json_decode(file_get_contents('data.json'), true);
    deleteIndex($client);
    createIndex($client);
    indexAllDocuments($client, $profiles);
    */

    $result = search($client, [
        'bool' => [
            'must' => [
                'nested' => [
                    'path' => 'profile_projects',
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'bool' => [
                                    'should' => [
                                        [
                                            'bool' => [
                                                'must' => [
                                                    ['term' => ['profile_projects.project_id' => 11]],
                                                    ['term' => ['profile_projects.role_id' => 6]],
                                                ]
                                            ]
                                        ],
                                        [
                                            'bool' => [
                                                'must' => [
                                                    ['term' => ['profile_projects.project_id' => 2]],
                                                    ['term' => ['profile_projects.role_id' => 5]],
                                                ]
                                            ]
                                        ],

                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'must_not' => [
                'nested' => [
                    'path' => 'profile_projects',
                    'query' => [
                        'constant_score' => [
                            'filter' => [
                                'bool' => [
                                    'should' => [
                                        [
                                            'bool' => [
                                                'must' => [
                                                    ['term' => ['profile_projects.project_id' => 8]],
                                                    ['term' => ['profile_projects.role_id' => 7]],
                                                ]
                                            ]
                                        ],
                                        [
                                            'bool' => [
                                                'must' => [
                                                    ['term' => ['profile_projects.project_id' => 15]],
                                                    ['term' => ['profile_projects.role_id' => 5]],
                                                ]
                                            ]
                                        ],
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ]);

    dd($result);


