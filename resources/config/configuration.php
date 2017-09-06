<?php

return [
    'days' => [
        'required' => true,
        'type' => 'anomaly.field_type.select',
        'config' => [
            'options' => [30 => 30, 60 => 60, 90 => 90]
        ],
    ],
    'view_id' => [
        'required' => true,
        'type' => 'anomaly.field_type.text',
    ],
    'auth_file' => [
        'required' => true,
        'type' => 'anomaly.field_type.file',
    ],
];
