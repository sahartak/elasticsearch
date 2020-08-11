<?php
use \Elasticsearch\Client;

function dd($data) {
    echo '<pre>';
    print_r($data);
    die;
}

function ddd($data) {
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}

function createIndex(Client $client) {
    $params = [
        'index' => 'profile_index',
        'body' => [
            'mappings' => [
                '_source' => [
                    'enabled' => true
                ],
                'properties' => [
                    'profile_projects' => [
                        'type' => 'nested'
                    ],
                    'color' => [
                        'type' => 'keyword'
                    ],
                    'size' => [
                        'type' => 'keyword'
                    ],
                    'favoriteFruit' => [
                        'type' => 'keyword'
                    ]
                ]
            ]
        ]
    ];

    $response = $client->indices()->create($params);
    return $response;
}

function deleteIndex(Client $client) {
    $params = ['index' => 'profile_index'];
    return $client->indices()->delete($params);
}

function indexDocument(Client $client, array $data) {
    $colors = ['Red', 'Blue', 'Green'];
    $data['color'] = $colors[rand(0, 2)];
    $sizes = ['s', 'm', 'xl'];
    $data['size'] = $sizes[rand(0, 2)];
    $params = [
        'index' => 'profile_index',
        'id'    => $data['id'],
        'body'  => $data
    ];

    return $client->index($params);
}

function search(Client $client, $params) {
    $params = [
        'index' => 'profile_index',
        'body'  => [
            'query' => $params
        ]
    ];

    return $client->search($params);
}

function indexAllDocuments(Client $client, array $profiles) {
    foreach ($profiles as $profile) {
        indexDocument($client, $profile);
    }
}

function fitterArray() {
    return [
        'nested' => [
            'path' => 'profile_projects',
            'query' => [
                'bool' => [
                    'must' => [
                        [
                            'bool' => [
                                'filter' => [
                                    ['term' => ['profile_projects.project_id' => 11]],
                                    ['term' => ['profile_projects.role_id' => 6]],
                                ]
                            ]
                        ],
                        [
                            'bool' => [
                                'filter' => [
                                    ['term' => ['profile_projects.id' => 4]],
                                    ['term' => ['profile_projects.name' => 'Lidia Gill']],
                                ]
                            ]
                        ],
                    ]

                ]
            ]
        ]
    ];
}