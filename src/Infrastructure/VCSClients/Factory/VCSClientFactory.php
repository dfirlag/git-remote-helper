<?php
declare(strict_types=1);

namespace App\Infrastructure\VCSClients\Factory;

use App\Domain\Exception\InvalidVCSClientException;
use App\Domain\VCSClient;
use App\Infrastructure\VCSClients\Github;

/**
 * Class VCSClientFactory
 *
 * @package App\Application\Factory
 */
class VCSClientFactory {

    /**
     * @param string $clientName
     * @return VCSClient
     * @throws InvalidVCSClientException
     */
    public function createByName(string $clientName): VCSClient {
        switch ($clientName) {
            case VCSClient::GITHUB_CLIENT:
                return new Github();
            default:
                throw new InvalidVCSClientException("Client $clientName does not exists");
        }
    }

    /**
     * @return VCSClient
     */
    public function createDefault(): VCSClient {
        return new Github();
    }
}