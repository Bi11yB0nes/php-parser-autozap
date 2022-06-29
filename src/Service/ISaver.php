<?php

declare(strict_types=1);

namespace App\Service;

interface ISaver
{
    public function saveData(array $data);
}
