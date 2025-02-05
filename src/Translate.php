<?php

namespace YandexTranslate;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use YandexTranslate\Enums\FormatEnum;
use YandexTranslate\Enums\LanguageEnum;

class Translate
{
    private string $apiKey;
    private string $apiUrl;
    private string $sourceLanguage;
    private FormatEnum $format = FormatEnum::FORMAT_UNSPECIFIED;
    private bool $speller = false;
    private ?string $folderId = '';

    public function __construct()
    {
        $this->apiKey = config('yandex-translate.api-key');
        $this->apiUrl = config('yandex-translate.api-url');
        $this->folderId = config('yandex-translate.folder-id');

        $this->sourceLanguage = config('app.locale');
    }

    public function setSourceLanguage(LanguageEnum|string $languageEnum): self
    {
        if ($languageEnum instanceof LanguageEnum) {
            $languageEnum = $languageEnum->value;
        }

        $this->sourceLanguage = $languageEnum;

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

    public function translate(string $targetLanguageCode, string $text): string
    {
        $response = Http::withHeaders(['Authorization' => 'Api-Key ' . $this->apiKey])
            ->post($this->apiUrl, [
                "sourceLanguageCode" => $this->sourceLanguage,
                "targetLanguageCode" => $targetLanguageCode,
                "format" => $this->format->value,
                "texts" => [$text],
                "folderId" => $this->folderId,
                "speller" => $this->speller,
            ]);

        $response->onError(fn (Response $response) => throw
            new \Exception(
                $response->body(),
                $response->status()
            ));

        return Arr::first($response->object()->translations)?->text;
    }
}
