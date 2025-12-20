<?php

return [
    'disk' => env('MEDIA_DISK', 'public'),
    'url' => env('MEDIA_URL', '/media'),
    'fallback' => [
        'room' => 'images/default-room.jpg',
        'video' => 'images/video-default.jpg',
        'meal' => 'images/meal-default.jpg',
        'default' => 'images/no-image.png',
    ],
];
