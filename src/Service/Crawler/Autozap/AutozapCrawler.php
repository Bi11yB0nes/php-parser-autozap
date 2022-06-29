<?php

declare(strict_types=1);

namespace App\Service\Crawler\Autozap;

use App\Dto\Good;
use App\Service\Crawler\ICrawler;
use DiDom\Document;
use DiDom\Exceptions\InvalidSelectorException;
use Exception;

class AutozapCrawler implements ICrawler
{

    private const LINK_PATTERN = 'https://www.autozap.ru';
    private const NO_RESULT_MESSAGE = 'Данного товара и его аналогов временно нет в наличии' . PHP_EOL;

    public function __construct(
        private readonly string $product
    )
    {
    }

    /**
     * @throws InvalidSelectorException
     * @throws Exception
     * @return Good[]
     */
    public function getData(): array
    {
        $document = new Document($this->getSearchLink(), true);
        if ($this->isEmptyResult($document)) {
            throw new Exception(self::NO_RESULT_MESSAGE);
        }
        $document = $this->getFirstVendorPage($document);

        $result = (new CertainVendorPageCrawler($document))->getData();
        if (!$result) {
            throw new Exception(self::NO_RESULT_MESSAGE);
        }

        return $result;
    }

    private function getSearchLink(): string
    {
        return self::LINK_PATTERN . '/goods/' . str_replace(' ', '', $this->product);
    }

    /**
     * @throws InvalidSelectorException
     */
    private function isEmptyResult(Document $document): bool
    {
        $tableElements = $document->find('#tabGoods > tr');
        return count($tableElements) == 0;
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getFirstVendorPage(Document $document): Document
    {
        $linkElements = $document->find('#goodLnk1');
        if (count($linkElements) > 0) {
            $singleVendorPageLink = self::LINK_PATTERN . $linkElements[0]->attr('href');
            return new Document($singleVendorPageLink, true);
        }
        return $document;
    }

}
