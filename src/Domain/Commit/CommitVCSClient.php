<?php

declare(strict_types=1);

namespace App\Domain\Commit;

use App\Domain\ValueObject\Branch;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;

interface CommitVCSClient {

    public function getLastCommitSha(OwnerName $owner, RepositoryName $repository, Branch $branch): string;
}