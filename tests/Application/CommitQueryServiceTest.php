<?php

declare(strict_types=1);

namespace App\Tests\Application;

use App\Application\CommitQueryService;
use App\Application\RepositoryQueryService;
use App\Domain\Exception\InvalidVCSClientException;
use App\Domain\Repo\Exception\InvalidRepositoryException;
use App\Domain\ValueObject\Branch;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;
use App\Domain\VCSClient;
use App\Infrastructure\VCSClients\Factory\VCSClientFactory;
use PHPUnit\Framework\TestCase;

class CommitQueryServiceTest extends TestCase {

    public function testGetLastCommitShaWithSpecifiedClientWillReturnCorrectSha()
    {
        $vcsClientFactoryMock = $this->getMockBuilder(VCSClientFactory::class)
                                     ->onlyMethods(['createByName', 'createDefault'])
                                     ->getMock();

        $vcsClientMock = $this->getMockForAbstractClass(VCSClient::class);

        $repositoryQueryService = new RepositoryQueryService($vcsClientFactoryMock);

        $commitQueryService = new CommitQueryService(
            $vcsClientFactoryMock,
            $repositoryQueryService
        );

        $owner = "owner";
        $repository = "repository";
        $branch = "branch";
        $client = VCSClient::GITHUB_CLIENT;
        $sha = "test_commit_sha";

        $vcsClientMock->expects($this->once())
                      ->method('getLastCommitSha')
                      ->with(
                          new OwnerName($owner),
                          new RepositoryName($repository),
                          new Branch($branch)
                      )
                      ->willReturn($sha);

        $vcsClientMock->expects($this->once())
                      ->method('isRepoValid')
                      ->with(
                          new OwnerName($owner),
                          new RepositoryName($repository)
                      )
                      ->willReturn(true);

        $vcsClientFactoryMock->expects($this->once())
                             ->method('createByName')
                             ->with($client)
                             ->willReturn($vcsClientMock);

        $commitDto = $commitQueryService->getLastCommitSha(
            $owner,
            $repository,
            $branch,
            $client
        );

        $this->assertSame($sha, $commitDto->getSha());
    }

    public function testGetLastCommitShaWithDefaultClientWillReturnCorrectSha()
    {
        $vcsClientFactoryMock = $this->getMockBuilder(VCSClientFactory::class)
                                     ->onlyMethods(['createByName', 'createDefault'])
                                     ->getMock();

        $vcsClientMock = $this->getMockForAbstractClass(VCSClient::class);

        $repositoryQueryService = new RepositoryQueryService($vcsClientFactoryMock);

        $commitQueryService = new CommitQueryService(
            $vcsClientFactoryMock,
            $repositoryQueryService
        );

        $owner = "owner";
        $repository = "repository";
        $branch = "branch";
        $sha = "test_commit_sha";

        $vcsClientMock->expects($this->once())
                      ->method('getLastCommitSha')
                      ->with(
                          new OwnerName($owner),
                          new RepositoryName($repository),
                          new Branch($branch)
                      )
                      ->willReturn($sha);

        $vcsClientMock->expects($this->once())
                      ->method('isRepoValid')
                      ->with(
                          new OwnerName($owner),
                          new RepositoryName($repository)
                      )
                      ->willReturn(true);

        $vcsClientFactoryMock->expects($this->once())
                             ->method('createDefault')
                             ->willReturn($vcsClientMock);

        $commitDto = $commitQueryService->getLastCommitSha(
            $owner,
            $repository,
            $branch
        );

        $this->assertSame($sha, $commitDto->getSha());
    }

    public function testGetLastCommitWithInvalidRepository()
    {
        $vcsClientFactoryMock = $this->getMockBuilder(VCSClientFactory::class)
                                     ->onlyMethods(['createByName', 'createDefault'])
                                     ->getMock();

        $vcsClientMock = $this->getMockForAbstractClass(VCSClient::class);

        $repositoryQueryService = new RepositoryQueryService($vcsClientFactoryMock);

        $commitQueryService = new CommitQueryService(
            $vcsClientFactoryMock,
            $repositoryQueryService
        );

        $owner = "owner";
        $repository = "repository";
        $branch = "branch";
        $client = VCSClient::GITHUB_CLIENT;

        $vcsClientMock->expects($this->never())
                      ->method('getLastCommitSha');

        $vcsClientMock->expects($this->once())
                      ->method('isRepoValid')
                      ->with(
                          new OwnerName($owner),
                          new RepositoryName($repository)
                      )
                      ->willReturn(false);

        $vcsClientFactoryMock->expects($this->once())
                             ->method('createByName')
                             ->with($client)
                             ->willReturn($vcsClientMock);

        $this->expectException(InvalidRepositoryException::class);

        $commitQueryService->getLastCommitSha($owner, $repository, $branch, $client);
    }

    public function testGetLastCommitWithInvalidClient()
    {
        $vcsClientFactoryMock = $this->getMockBuilder(VCSClientFactory::class)
                                     ->onlyMethods(['createByName', 'createDefault'])
                                     ->getMock();

        $vcsClientMock = $this->getMockForAbstractClass(VCSClient::class);

        $repositoryQueryService = new RepositoryQueryService($vcsClientFactoryMock);

        $commitQueryService = new CommitQueryService(
            $vcsClientFactoryMock,
            $repositoryQueryService
        );

        $owner = "owner";
        $repository = "repository";
        $branch = "branch";
        $client = 'invalid_client';

        $vcsClientMock->expects($this->never())
                      ->method('getLastCommitSha');

        $vcsClientMock->expects($this->never())
                      ->method('isRepoValid');

        $vcsClientFactoryMock->expects($this->once())
                             ->method('createByName')
                             ->with($client)
                             ->willThrowException(new InvalidVCSClientException());

        $this->expectException(InvalidVCSClientException::class);

        $commitQueryService->getLastCommitSha($owner, $repository, $branch, $client);
    }
}