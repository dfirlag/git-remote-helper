<?php

declare(strict_types=1);

namespace App\Domain\Commit\Dto;

class CommitDto {

    /**
     * @var string
     */
    private $sha;

    public function __construct(string $sha) {
        $this->sha = $sha;
    }

    public function getSha(): string {
        return $this->sha;
    }
}