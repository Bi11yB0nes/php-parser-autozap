<?php

declare(strict_types=1);

namespace App\Service\Saver;

use App\Dto\Good;
use App\Service\ISaver;

class GoodsSaver implements ISaver
{

    private string $fileName;

    public function __construct(
        private readonly string $path = '..' . DIRECTORY_SEPARATOR . 'results' . DIRECTORY_SEPARATOR
    )
    {
    }

    /**
     * @param Good[] $data
     */
    public function saveData(array $data): bool
    {
        $fileName = $this->path . time() . '.txt';
        $content = self::getConvertedData($data);
        $file = fopen($fileName, 'a');
        if (!$file) {
            return false;
        }
        fwrite($file, $content);
        fclose($file);
        $this->fileName = $fileName;
        return true;
    }

    /**
     * @param Good[] $goods
     */
    private function getConvertedData(array $goods): string
    {
        $data = [];
        foreach ($goods as $good) {
            $data[] = $good->getAsArray();
        }
        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }
}
