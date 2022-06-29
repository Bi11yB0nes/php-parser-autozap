<?php

declare(strict_types=1);

namespace App\Service;

use App\Service\Crawler\ICrawler;

class GoodsService
{
    const ERROR_MESSAGE = 'Не удалось сохранить результат' . PHP_EOL;
    const SUCCESS_MESSAGE = 'Результат сохранён в файл : ';

    public function __construct(
        private readonly ICrawler $crawler,
        private readonly ISaver   $saver,
    )
    {
    }

    public function saveData(): bool
    {
        $data = $this->crawler->getData();
        if ($this->saver->saveData($data)) {
            echo self::SUCCESS_MESSAGE . $this->saver->getFileName() . PHP_EOL;
            return false;
        }
        echo self::ERROR_MESSAGE;
        return false;
    }
}
