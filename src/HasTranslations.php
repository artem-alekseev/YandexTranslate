<?php

namespace YandexTranslate;

use Illuminate\Support\Arr;
use Spatie\Translatable\HasTranslations as BaseHasTranslations;
use YandexTranslate\Facades\Translatable;

trait HasTranslations
{
    use BaseHasTranslations;

    public function setAttribute($key, $value)
    {
        if (! $this->isTranslatableAttribute($key)) {
            return parent::setAttribute($key, $value);
        }

        if (is_array($value) && (! array_is_list($value) || count($value) === 0)) {
            return $this->setTranslations($key, $value);
        }

        $this->updateTranslatableField($key, $value);

        return $this->setTranslation($key, $this->getLocale(), $value);
    }
    public function updateTranslatableField($key, $value): void
    {
        $locales = Arr::except(config('yandex-translate.locales'), $this->getLocale());

        foreach ($locales as $locale) {
            $translate = Translatable::translate($locale, $value);

            $this->setTranslation($key, $this->getLocale(), $translate);
        }
    }
}