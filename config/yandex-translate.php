<?php

use YandexTranslate\Enums\LanguageEnum;

return [
    'api-key' => env('YANDEX_TRANSLATE_API_KEY'),
    'api-url' => env('YANDEX_TRANSLATE_API_URL', 'https://translate.api.cloud.yandex.net/translate/v2/translate'),
    'folder-id' => env('YANDEX_TRANSLATE_FOLDER_ID'),
    'locales' => env('YANDEX_TRANSLATE_LOCALES') ? explode(',', env('YANDEX_TRANSLATE_LOCALES')) : [
        LanguageEnum::RUSSIAN->value,
        LanguageEnum::ENGLISH->value,
        LanguageEnum::CHINESE->value,
    ] ,
];