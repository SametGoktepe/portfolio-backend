<?php

namespace Src\Domain\About\Repositories;

use Src\Domain\About\Models\About;
use Src\Domain\About\Models\AboutId;

interface AboutRepositoryInterface
{
    public function find(AboutId $id): ?About;
    
    public function findFirst(): ?About;
    
    public function save(About $about): void;
    
    public function update(About $about): void;
    
    public function delete(AboutId $id): void;
    
    public function exists(AboutId $id): bool;
}