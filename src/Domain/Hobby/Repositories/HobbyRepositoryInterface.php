<?php

namespace Src\Domain\Hobby\Repositories;

use Src\Domain\Hobby\Models\Hobby;
use Src\Domain\Hobby\Models\HobbyId;

interface HobbyRepositoryInterface
{
    public function find(HobbyId $id): ?Hobby;
    
    public function findAll(): array;
    
    public function save(Hobby $hobby): void;
    
    public function update(Hobby $hobby): void;
    
    public function delete(HobbyId $id): void;
    
    public function exists(HobbyId $id): bool;
}

