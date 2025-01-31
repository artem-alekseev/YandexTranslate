<?php

namespace YandexTranslate;

use Illuminate\Support\Facades\Facade;

class TranslateFacade extends Facade
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