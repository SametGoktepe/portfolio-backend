<?php

namespace Src\Domain\Category\Repositories;

use Src\Domain\Category\Models\Category;
use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Category\ValueObjects\CategorySlug;

interface CategoryRepositoryInterface
{
    public function find(CategoryId $id): ?Category;
    
    public function findBySlug(CategorySlug $slug): ?Category;
    
    public function findAll(): array;
    
    public function findWithSkills(CategoryId $id): ?Category;
    
    public function save(Category $category): void;
    
    public function update(Category $category): void;
    
    public function delete(CategoryId $id): void;
    
    public function exists(CategoryId $id): bool;
    
    public function slugExists(CategorySlug $slug, ?CategoryId $excludeId = null): bool;
}