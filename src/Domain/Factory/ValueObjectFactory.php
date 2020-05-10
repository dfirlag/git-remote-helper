<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\ValueObject\Branch;
use App\Domain\ValueObject\OwnerName;
use App\Domain\ValueObject\RepositoryName;

class ValueObjectFactory {

    public static function createRepository(string $repository): RepositoryName {
        return new RepositoryName($repository);
    }

    public static function createOwner(string $owner): OwnerName {
        return new OwnerName($owner);
    }

    public static function createBranch(string $branch): Branch {
        return new Branch($branch);
    }
}