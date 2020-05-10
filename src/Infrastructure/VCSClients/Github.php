<?php

namespace App\Infrastructure\VCSClients;

use App\Domain\Commit\Exception\CommitResultException;
use App\Domain\VCSClient;
use App\Domain\ValueObject\Branch;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;
use Github\Client;

final class Github implements VCSClient {

    const NOT_FOUND_ERROR_CODE = 404;

    /**
     * @var Client
     */
    private $client;

    public function __construct() {
        $this->client = new Client();
    }

    /**
     * @param OwnerName      $owner
     * @param RepositoryName $repository
     * @param Branch         $branch
     * @return string
     * @throws CommitResultException
     */
    public function getLastCommitSha(OwnerName $owner, RepositoryName $repository, Branch $branch): string {
        try {
            $result = $this->client->api('repo')
                                   ->commits()
                                   ->all($owner->getOwner(), $repository->getRepository(), ['sha' => $branch->getBranch()]);
        } catch (\RuntimeException $e) {
            if ($e->getCode() === self::NOT_FOUND_ERROR_CODE) {
                return false;
            }

            throw $e;
        }
        if (!isset($result[0]['sha'])) {
            throw new CommitResultException("Cannot find last commit");
        }

        return $result[0]['sha'];
    }

    public function isRepoValid(OwnerName $owner, RepositoryName $repository): bool {
        try {
            $this->client->api('repo')
                         ->show($owner->getOwner(), $repository->getRepository());
        } catch (\RuntimeException $e) {
            if ($e->getCode() === self::NOT_FOUND_ERROR_CODE) {
                return false;
            }

            throw $e;
        }

        return true;
    }
}