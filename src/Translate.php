<?php

namespace YandexTranslate;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class Translate
{
    private string $apiKey;
    private string $apiUrl;
    private LanguageEnum $sourceLanguage = LanguageEnum::RUSSIAN;
    private LanguageEnum $targetLanguage = LanguageEnum::ENGLISH;
    private FormatEnum $format = FormatEnum::FORMAT_UNSPECIFIED;
    private bool $speller = false;
    private Collection $texts;

    private string $folderId = '';

    public function __construct()
    {
        $this->apiKey = config('yandex-translate.api-key');
        $this->apiUrl = config('yandex-translate.api-url');

        $this->texts = collect();
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

    public function addText(string $key, string $text): self
    {
        $this->texts->offsetSet($key, $text);

        return $this;
    }

    public function setTexts(array|Collection|Model $texts): self
    {
        if ($texts instanceof Model) {
            $texts = $texts->only($texts->translatableFields ?? []);
        }

        $this->texts = collect($texts);

        return $this;
    }

    public function translate(): Collection
    {
        if ($this->texts->isEmpty()) {
            throw new \Exception('Not add text, or not fill translatableFields in Model', 500);
        }

        $response = Http::withHeaders(['Authorization' => 'Api-Key ' . $this->apiKey])
            ->post($this->apiUrl, [
                "sourceLanguageCode" => $this->sourceLanguage->value,
                "targetLanguageCode" => $this->targetLanguage->value,
                "format" => $this->format->value,
                "texts" => $this->texts->values()->toArray(),
                "folderId" => $this->folderId,
                "speller" => $this->speller,
            ]);

        $response->onError(fn(Response $response) => throw new \Exception($response->body(), $response->status()));
        $translations = $response->object()->translations;
        $texts = $this->texts->keys()
            ->mapWithKeys(fn($key, $value) => [$key => Arr::get($translations, $value)?->text]);

        return $texts;
    }
}
