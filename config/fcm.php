<?php

return [
    'driver' => env('FCM_PROTOCOL', 'http'),
    'log_enabled' => false,

    'http' => [
        'server_key' => env('FCM_SERVER_KEY', 'AAAAN9g3Gvc:APA91bFryC_JncBu3Xvpja2ARYoFsGIAMug3mCnnvornN3EhDWbmA6hgaJmlWcz_T0otLbwlG8XC4uxoUkxeYYYUfZ2jMdRGCnaTBxNIYZVAt0pkmqxiJ7soUfbkcpYZP4ynriFyTvVA'),
        'sender_id' => env('FCM_SENDER_ID', '239850691319'),
        'server_send_url' => 'https://fcm.googleapis.com/fcm/send',
        'server_group_url' => 'https://android.googleapis.com/gcm/notification',
        'timeout' => 120.0, // in second
    ],
];
