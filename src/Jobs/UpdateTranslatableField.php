<?php

namespace YandexTranslate\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Queue\Queueable;
use YandexTranslate\Facades\Translatable;

class UpdateTranslatableField implements ShouldQueue
{
    use Queueable;

    private array $locales;

    public function __construct(
        public Model $model,
        public string $key,
        public string $value,
    ) {
        $this->locales = config('yandex-translate.locales');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        foreach ($this->locales as $locale) {
            if ($locale == $this->value) {
                continue;
            }

            $translate = Translatable::translate($locale, $this->value);
            $this->model->setTranslation($this->key, $locale, $translate);
        }
    }
}
