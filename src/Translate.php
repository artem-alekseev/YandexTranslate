<?php

namespace YandexTranslate;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Translate
{
    private string $apiKey;
    private LanguageEnum $sourceLanguage = LanguageEnum::RUSSIAN;
    private LanguageEnum $targetLanguage = LanguageEnum::ENGLISH;
    private FormatEnum $format = FormatEnum::FORMAT_UNSPECIFIED;
    private bool $speller = false;
    private Collection $texts;

    private string $folderId = '';

    public function __construct()
    {
        $this->apiKey = config('yandex-translate.api-key');
    }

    public function setSourceLanguage(LanguageEnum $languageEnum): self
    {
        $this->sourceLanguage = $languageEnum;

        return $this;
    }

    public function setTargetLanguage(LanguageEnum $languageEnum): self
    {
        $this->targetLanguage = $languageEnum;

        return $this;
    }

    public function setFormat(FormatEnum $formatEnum): self
    {
        $this->format = $formatEnum;

        return $this;
    }

    public function enableSpellChecker(): self
    {
        $this->speller = true;

        return $this;
    }

    public function addText(string $key, string $text)
    {
        $this->texts->push([$key => $text]);

        return $this;
    }

    public function translate()
    {
        dd($this->texts);

        $response = Http::withHeaders(['Authorization' => 'Api-Key ' . $this->apiKey])
            ->post('https://translate.api.cloud.yandex.net/translate/v2/translate', [
                "sourceLanguageCode" => $this->sourceLanguage->value,
                "targetLanguageCode" => $this->targetLanguage->value,
                "format" => $this->format->value,
                "texts" => $this->texts->toArray(),
                "folderId" => $this->folderId,
                "speller" => $this->speller,
            ]);

        $trans = [];

        foreach ($response->translations as $key => $value) {
            $trans[$keys[$key]] = $value->text;
        }

        return $trans;
    }
}
