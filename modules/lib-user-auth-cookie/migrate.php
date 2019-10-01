<?php

return [
    'LibUserAuthCookie\\Model\\UserAuthCookie' => [
        'fields' => [
            'id' => [
                'type' => 'INT',
                'attrs' => [
                    'unsigned' => true,
                    'primary_key' => true,
                    'auto_increment' => true
                ]
            ],
            'user' => [
                'type' => 'INT',
                'attrs' => [
                    'null' => false,
                    'unsigned' => true 
                ]
            ],
            'hash' => [
                'type' => 'VARCHAR',
                'length' => 190,
                'attrs' => [
                    'unique' => true,
                    'null' => false 
                ]
            ],
            'expires' => [
                'type' => 'DATETIME',
                'attrs' => [
                    'null' => false 
                ]
            ],
            'updated' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP',
                    'update' => 'CURRENT_TIMESTAMP'
                ]
            ],
            'created' => [
                'type' => 'TIMESTAMP',
                'attrs' => [
                    'default' => 'CURRENT_TIMESTAMP'
                ]
            ]
        ]
    ]
];