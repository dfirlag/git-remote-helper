<?php

declare(strict_types=1);

namespace App\Domain\Commit;

use App\Domain\Commit\Dto\CommitDto;
use App\Domain\Repo\RepositoryService;
use App\Domain\ValueObject\Branch;

class CommitService {

    /**
     * @var CommitVCSClient
     */
    private $VCSClient;

    /**
     * @var RepositoryService
     */
    private $repo;

    public function __construct(CommitVCSClient $client, RepositoryService $repo) {
        $this->VCSClient = $client;
        $this->repo = $repo;
    }

    public function getLastCommitSha(Branch $branch): CommitDto {
        $shaString = $this->VCSClient->getLastCommitSha($this->repo->getOwner(), $this->repo->getRepository(), $branch);

        return new CommitDto($shaString);
    }
}