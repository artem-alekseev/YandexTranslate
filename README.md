# YandexTranslate
[![Latest Version on Packagist](https://img.shields.io/packagist/v/artem-alekseev/yandex-translate.svg?style=flat-square)](https://packagist.org/packages/artem-alekseev/yandex-translate)
[![MIT Licensed](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Total Downloads](https://img.shields.io/packagist/dt/artem-alekseev/yandex-translate.svg?style=flat-square)](https://packagist.org/packages/artem-alekseev/yandex-translate)

**YandexTranslate** — это библиотека для взаимодействия с API Яндекс.Переводчика, позволяющая легко интегрировать возможности машинного перевода в ваши приложения.

## Возможности

- Поддержка множества языков
- Перевод текста
- Автоматический перевод моделей (Использует пакет [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable))

## Установка

#### Необходимые зависимости
    PHP 8.1+
    Laravel 9+
    MySQL 5.7 или выше

#### Установите библиотеку с помощью Composer:

```bash
composer require artem-alekseev/yandex-translate
```

```bash
php artisan vendor:publish --tag=yandex-translate
```

#### Сконфигурируйте .env файл

```dotenv
YANDEX_TRANSLATE_API_KEY=Ваш API ключ из Yandex Cloud
YANDEX_TRANSLATE_LOCALES=ru,en,zh (Необходимо для автоматичесткого перевода)
YANDEX_TRANSLATE_API_URL=https://translate... (Не обязательный)
YANDEX_TRANSLATE_FOLDER_ID=dg3g4th56wwr6hs6r (Не обязательный)
YANDEX_TRANSLATE_FORMAT=FORMAT_UNSPECIFIED (Не обязательный)
```

## Использование

#### Для использования автоматического перевода

В вашу модель необходимо добавить трейт ```YandexTranslate\HasTranslations``` 
и указать какие поля необходимо перевести
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use YandexTranslate\HasTranslations;

class Example extends Model
{
    use HasTranslations;

    protected $fillable = [
        'title',
        'description',
    ];

    /**
    * Поля которые будут автоматически переведены при
    * сохранении или обновлении сущности
    */
    public $translatable = [
        'title',
        'description',
    ];
}
```

Перевод запускатся в очередях (Если они у вас не в режиме sync) для запуска очередей используйте команду

```bash
php artisan queue:work --queue=translate
```

#### Простое использование

```php
<?php

namespace App\Http\Controllers;

use YandexTranslate\Enums\FormatEnum;
use YandexTranslate\Enums\LanguageEnum;
use YandexTranslate\Facades\Translatable;

class ExampleController extends Controller
{
    public function index()
    {
        $text = Translatable::enableSpellChecker() //Не обязательный
            ->setFormat(FormatEnum::HTML) //Не обязательный
            ->setSourceLanguage(LanguageEnum::RUSSIAN) //Не обязательный
            ->translate(LanguageEnum::ENGLISH, 'Переведи мне этот текст');

        return response()->json(['data' => $text]);
    }
}

```

## License

The MIT License (MIT). Please see License File for more information.