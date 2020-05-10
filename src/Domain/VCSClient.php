<?php

declare(strict_types=1);

namespace App\Domain;

use App\Domain\Commit\CommitVCSClient;
use App\Domain\Repo\RepositoryVCSClient;

interface VCSClient extends RepositoryVCSClient, CommitVCSClient {

    const GITHUB_CLIENT = 'github';
}