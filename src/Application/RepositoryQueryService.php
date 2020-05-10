<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Repo\Builder\RepositoryServiceBuilder;
use App\Domain\Repo\Exception\InvalidRepositoryException;
use App\Domain\Repo\RepositoryService;
use App\Domain\VCSClient;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;
use App\Infrastructure\VCSClients\Factory\VCSClientFactory;

class RepositoryQueryService {

    private $VCSClientFactory;

    public function __construct(VCSClientFactory $clientFactory) {
        $this->VCSClientFactory = $clientFactory;
    }

    /**
     * @param OwnerName      $ownerValueObject
     * @param RepositoryName $repositoryValueObject
     * @param VCSClient      $vcsClient
     * @return RepositoryService
     * @throws InvalidRepositoryException
     */
    public function getRepo(OwnerName $ownerValueObject, RepositoryName $repositoryValueObject, VCSClient $vcsClient): RepositoryService {
        $repo = RepositoryServiceBuilder::create()
                                        ->setVcsClient($vcsClient)
                                        ->setOwner($ownerValueObject)
                                        ->setRepository($repositoryValueObject)
                                        ->build();
        if (!$repo->isRepositoryValid()) {
            throw new InvalidRepositoryException();
        }

        return $repo;
    }
}