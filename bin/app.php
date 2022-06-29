<?php

declare(strict_types=1);

require_once('../vendor/autoload.php');

use App\Service\Crawler\Autozap\AutozapCrawler;
use App\Service\GoodsService;
use App\Service\Saver\GoodsSaver;

const ERROR_MESSAGE = 'Введите товар для поиска'. PHP_EOL;

try {
    init($argv);
} catch (Exception $e) {
    echo $e->getMessage();
}

function init($argv): void
{
    if (!empty($argv[1])) {
        $product = $argv[1];
        $crawler = new AutozapCrawler($product);
        $saver = new GoodsSaver();
        $service = new GoodsService($crawler, $saver);
        $service->saveData();
    } else {
        echo ERROR_MESSAGE . PHP_EOL;
    }

    echo PHP_EOL;
}
