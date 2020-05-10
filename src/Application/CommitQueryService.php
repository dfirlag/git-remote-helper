<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Commit\Builder\CommitServiceBuilder;
use App\Domain\Commit\Dto\CommitDto;
use App\Domain\Repo\Exception\InvalidRepositoryException;
use App\Infrastructure\VCSClients\Factory\VCSClientFactory;
use App\Domain\Exception\InvalidVCSClientException;
use App\Domain\Factory\ValueObjectFactory;

class CommitQueryService {

    /**
     * @var VCSClientFactory
     */
    private $vcsClientFactory;

    /**
     * @var RepositoryQueryService
     */
    private $repositoryQueryService;

    public function __construct(VCSClientFactory $clientFactory, RepositoryQueryService $repositoryQueryService) {
        $this->vcsClientFactory = $clientFactory;
        $this->repositoryQueryService = $repositoryQueryService;
    }

    /**
     * @param String $owner
     * @param String $repo
     * @param String $branch
     * @param String $clientName
     * @return CommitDto
     * @throws InvalidRepositoryException
     * @throws InvalidVCSClientException
     */
    public function getLastCommitSha(string $owner, string $repo, string $branch, ?string $clientName = null): CommitDto {
        $ownerValueObject = ValueObjectFactory::createOwner($owner);
        $repoValueObject = ValueObjectFactory::createRepository($repo);
        if ($clientName === null) {
            $vcsClient = $this->vcsClientFactory->createDefault();
        } else {
            $vcsClient = $this->vcsClientFactory->createByName($clientName);
        }
        $repo = $this->repositoryQueryService->getRepo($ownerValueObject, $repoValueObject, $vcsClient);
        $commit = CommitServiceBuilder::create()
                                      ->setVCSClient($vcsClient)
                                      ->setRepo($repo)
                                      ->build();

        return $commit->getLastCommitSha(ValueObjectFactory::createBranch($branch));
    }
}