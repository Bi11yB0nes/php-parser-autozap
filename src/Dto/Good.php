<?php

declare(strict_types=1);

namespace App\Dto;

use JetBrains\PhpStorm\ArrayShape;

class Good
{

    public function __construct(
        private readonly string $id,
        private readonly string $name,
        private readonly string $price,
        private readonly string $article,
        private readonly string $brand,
        private readonly string $count,
        private readonly string $time,
    )
    {
    }

    #[ArrayShape([
        'id' => "string",
        'name' => "string",
        'price' => "string",
        'article' => "string",
        'brand' => "string",
        'count' => "string",
        'time' => "string"
    ])]
    public function getAsArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'article' => $this->article,
            'brand' => $this->brand,
            'count' => $this->count,
            'time' => $this->time,
        ];
    }

}
