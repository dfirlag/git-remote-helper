<?php

declare(strict_types=1);

namespace App\Domain\Repo;

use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;

interface RepositoryVCSClient {

    public function isRepoValid(OwnerName $owner, RepositoryName $repository): bool;
}