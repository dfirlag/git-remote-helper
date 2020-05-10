<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class OwnerName {

    /**
     * @var string
     */
    private $owner;

    public function __construct(string $repository) { $this->owner = $repository; }

    public function getOwner(): string {
        return $this->owner;
    }
}