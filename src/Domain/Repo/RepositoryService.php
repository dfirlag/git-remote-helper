<?php

declare(strict_types=1);

namespace App\Domain\Repo;

use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;

class RepositoryService {

    /**
     * @var RepositoryVCSClient
     */
    protected $vcsClient;

    /**
     * @var OwnerName
     */
    protected $owner;

    /**
     * @var RepositoryName
     */
    protected $repository;

    public function __construct(RepositoryVCSClient $vcsClient, OwnerName $owner, RepositoryName $repository) {
        $this->vcsClient = $vcsClient;
        $this->owner = $owner;
        $this->repository = $repository;
    }

    public function isRepositoryValid(): bool {
        return $this->vcsClient->isRepoValid($this->owner, $this->repository);
    }

    public function getOwner(): OwnerName {
        return $this->owner;
    }

    public function getRepository(): RepositoryName {
        return $this->repository;
    }
}