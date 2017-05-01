<?php
return [
    'settings' => [
        'displayErrorDetails' => true, // set to false in production
        'addContentLengthHeader' => false, // Allow the web server to send the content-length header

        // Renderer settings
        'renderer' => [
            'template_path' => __DIR__ . '/../templates/',
        ],

        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log',
            'level' => \Monolog\Logger::DEBUG,
        ],
        'api_url' => [
            'person' => 'https://person.clearbit.com/',
            'company' => 'https://company.clearbit.com/',
            'combined' => 'https://person-stream.clearbit.com/',
            'autocomplete' => 'https://autocomplete.clearbit.com/',
            'logo'=> 'https://logo.clearbit.com/'
        ],
        'uploadServiceUrl' => 'http://104.198.149.144:8080',
        'fileExtensions' => ['jpg' => '.jpg', 'png'=> '.png'],
    ],
];
