<?php

namespace YandexTranslate\Facades;

use Illuminate\Support\Facades\Facade;
use YandexTranslate\Enums\FormatEnum;
use YandexTranslate\Enums\LanguageEnum;
use YandexTranslate\Translate;

/**
 * @see \YandexTranslate\Translate
 *
 * @method static Translate setSourceLanguage(LanguageEnum|string $languageEnum)
 * @method static Translate setFormat(FormatEnum $formatEnum)
 * @method static Translate enableSpellChecker()
 * @method static string translate(string $targetLanguageCode, string $text)
 *
 */
class Translatable extends Facade
{
    /**
     * @codeCoverageIgnore
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return Translate::class;
    }
}
