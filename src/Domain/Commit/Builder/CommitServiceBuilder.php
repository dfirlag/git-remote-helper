<?php

declare(strict_types=1);

namespace App\Domain\Commit\Builder;

use App\Domain\Commit\CommitService;
use App\Domain\Commit\CommitVCSClient;
use App\Domain\Repo\RepositoryService;

class CommitServiceBuilder {

    /**
     * @var CommitVCSClient
     */
    private $VCSClient;

    /**
     * @var RepositoryService
     */
    private $repo;

    public static function create(): CommitServiceBuilder {
        return new self();
    }

    public function setVCSClient(CommitVCSClient $VCSClient): self {
        $this->VCSClient = $VCSClient;

        return $this;
    }

    public function setRepo(RepositoryService $repo): self {
        $this->repo = $repo;

        return $this;
    }

    public function build(): CommitService {
        return new CommitService($this->VCSClient, $this->repo);
    }
}