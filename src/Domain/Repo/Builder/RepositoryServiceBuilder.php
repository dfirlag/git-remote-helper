<?php

declare(strict_types=1);

namespace App\Domain\Repo\Builder;

use App\Domain\Repo\RepositoryService;
use App\Domain\Repo\RepositoryVCSClient;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;

class RepositoryServiceBuilder {

    /**
     * @var RepositoryVCSClient
     */
    private $VCSClient;

    /**
     * @var RepositoryName
     */
    private $repository;

    /**
     * @var OwnerName
     */
    private $owner;

    public static function create(): RepositoryServiceBuilder {
        return new self();
    }

    public function setVcsClient(RepositoryVCSClient $vcsClient): self {
        $this->VCSClient = $vcsClient;

        return $this;
    }

    public function setRepository(RepositoryName $repository): self {
        $this->repository = $repository;

        return $this;
    }

    public function setOwner(OwnerName $owner): self {
        $this->owner = $owner;

        return $this;
    }

    public function build(): RepositoryService {
        return new RepositoryService($this->VCSClient, $this->owner, $this->repository);
    }
}