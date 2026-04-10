<?php

return [
    'max_upload_kb' => 64 * 1024,
    'max_upload_mb' => 64,
    'max_post_mb' => 128,

    'media_library_mimes' => [
        'jpg', 'jpeg', 'png', 'gif', 'webp',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip',
        'mp4', 'webm', 'mov',
    ],

    'conversation_attachment_mimes' => [
        'jpg', 'jpeg', 'png', 'gif',
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip',
        'mp4', 'webm', 'mov',
    ],

    'editor_image_mimes' => ['jpeg', 'jpg', 'png', 'gif', 'webp'],

    'video_extensions' => ['mp4', 'webm', 'mov'],
];