<?php

namespace YandexTranslate;

use Spatie\Translatable\HasTranslations as BaseHasTranslations;
use YandexTranslate\Jobs\UpdateTranslatableField;

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

        UpdateTranslatableField::dispatch($this, $key, $value)->onQueue('translate');

        return $this->setTranslation($key, $this->getLocale(), $value);
    }
}
