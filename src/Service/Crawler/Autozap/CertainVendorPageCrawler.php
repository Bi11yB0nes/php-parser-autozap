<?php

declare(strict_types=1);

namespace App\Service\Crawler\Autozap;

use App\Dto\Good;
use DiDom\Document;
use DiDom\Element;
use DiDom\Exceptions\InvalidSelectorException;

class CertainVendorPageCrawler
{
    public function __construct(
        private readonly Document $document
    )
    {
    }

    /**
     * @return Good[]
     * @throws InvalidSelectorException
     */
    public function getData(): array
    {
        $result = [];
        $goodsTableElement = $this->document->find('#tabGoods')[0];

        $headerElements = $goodsTableElement->findInDocument('tr.thead');

        if (count($headerElements) > 0) {
            foreach ($headerElements as $element)
                $element->remove();
        }

        $sectionHeaderElements = $goodsTableElement->findInDocument('tr.header_tr');

        if (count($sectionHeaderElements) > 0) {
            foreach ($sectionHeaderElements as $element)
                $element->remove();
        }

        $goodElements = $goodsTableElement->findInDocument('tr');
        if (count($goodElements) > 0) {
            $currentBrand = '';
            $currentArticle = '';
            $currentName = '';
            foreach ($goodElements as $element) {
                $brand = $this->getBrand($element);
                if ($brand) {
                    $currentBrand = $brand;
                }

                $article = $this->getArticle($element);
                if ($article) {
                    $currentArticle = $article;
                }

                $name = $this->getName($element);
                if ($name) {
                    $currentName = $name;
                }

                $id = $this->getId($element);
                $price = $this->getPrice($element);
                $count = $this->getCount($element);
                $time = $this->getTime($element);

                $result[] = new Good($id, $currentName, $price, $currentArticle, $currentBrand, $count, $time);
            }
        }
        return $result;
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getBrand(Element $element): string
    {
        $brandElement = $element->firstInDocument('.producer');
        $brandElement->firstInDocument('span')->remove();
        return $brandElement->text();
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getId(Element $element): string
    {
        $quantityElement = $element->firstInDocument('.storehouse-quantity');
        $unnecessaryElements = $quantityElement->findInDocument('input[name]');
        if (count($unnecessaryElements) > 0) {
            foreach ($unnecessaryElements as $element) {
                $element->remove();
            }
        }
        $idElement = $quantityElement->firstInDocument('input');
        return $idElement->attr('value');

    }

    /**
     * @throws InvalidSelectorException
     */
    private function getName(Element $element): string
    {
        $result = '';
        $nameElement = $element->firstInDocument('td.name a');
        if ($nameElement) {
            $result = $nameElement->text();
        }
        return $result;
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getPrice(Element $element): string
    {
        $idElement = $element->firstInDocument('.price > span[id]');
        return $idElement->text();
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getArticle(Element $element): string
    {
        $result = '';
        $articleElement = $element->firstInDocument('td.code');
        if ($articleElement) {
            $result = $articleElement->text();
        }
        return $result;
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getCount(Element $element): string
    {
        $countElement = $element->firstInDocument('.storehouse-quantity > span');
        return $countElement->text();
    }

    /**
     * @throws InvalidSelectorException
     */
    private function getTime(Element $element): string
    {
        $timeElement = $element->firstInDocument('td.article');
        return $timeElement->text();
    }

}
