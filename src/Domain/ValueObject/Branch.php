<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class Branch {

    /**
     * @var string
     */
    private $branch;

    public function __construct(string $branch) { $this->branch = $branch; }

    public function getBranch(): string {
        return $this->branch;
    }
}