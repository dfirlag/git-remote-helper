<?php

declare(strict_types=1);

namespace App\Domain\ValueObject;

class RepositoryName {

    /**
     * @var string
     */
    private $repository;

    public function __construct(string $repository) { $this->repository = $repository; }

    public function getRepository(): string {
        return $this->repository;
    }
}