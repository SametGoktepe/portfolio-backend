<?php

namespace Src\Domain\Reference\Repositories;

use Src\Domain\Reference\Models\Reference;
use Src\Domain\Reference\Models\ReferenceId;

interface ReferenceRepositoryInterface
{
    public function find(ReferenceId $id): ?Reference;

    public function findAll(): array;

    public function save(Reference $reference): void;

    public function update(Reference $reference): void;

    public function delete(ReferenceId $id): void;

    public function exists(ReferenceId $id): bool;
}

