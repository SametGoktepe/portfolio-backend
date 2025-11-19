<?php

namespace Src\Domain\Education\Repositories;

use Src\Domain\Education\Models\Education;
use Src\Domain\Education\Models\EducationId;

interface EducationRepositoryInterface
{
    public function find(EducationId $id): ?Education;

    public function findAll(): array;

    public function save(Education $education): void;

    public function update(Education $education): void;

    public function delete(EducationId $id): void;

    public function exists(EducationId $id): bool;
}

