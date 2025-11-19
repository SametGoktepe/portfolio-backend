<?php

namespace Src\Domain\Category\Services;

use Src\Domain\Category\Models\Category;
use Src\Domain\Category\Models\CategoryId;
use Src\Domain\Category\ValueObjects\CategoryName;
use Src\Domain\Category\ValueObjects\CategorySlug;
use Src\Domain\Category\Repositories\CategoryRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class CategoryService
{
    private CategoryRepositoryInterface $repository;

    public function __construct(CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createCategory(string $name, ?string $slug = null): Category
    {
        $categoryName = new CategoryName($name);

        $categorySlug = $slug
            ? new CategorySlug($slug)
            : CategorySlug::fromName($name);

        // Check if slug already exists
        if ($this->repository->slugExists($categorySlug)) {
            throw new DomainException("Category with slug '{$categorySlug->value()}' already exists");
        }

        $category = Category::create(
            CategoryId::generate(),
            $categoryName,
            $categorySlug
        );

        $this->repository->save($category);

        return $category;
    }

    public function findOrCreateCategory(string $name): Category
    {
        $slug = CategorySlug::fromName($name);

        $category = $this->repository->findBySlug($slug);

        if ($category) {
            return $category;
        }

        return $this->createCategory($name);
    }

    public function updateCategory(string $id, string $name, ?string $slug = null): Category
    {
        $categoryId = new CategoryId($id);
        $category = $this->repository->find($categoryId);

        if (!$category) {
            throw new DomainException('Category not found');
        }

        $categoryName = new CategoryName($name);
        $categorySlug = $slug
            ? new CategorySlug($slug)
            : CategorySlug::fromName($name);

        // Check if new slug conflicts with existing category
        if ($this->repository->slugExists($categorySlug, $categoryId)) {
            throw new DomainException("Category with slug '{$categorySlug->value()}' already exists");
        }

        $category->rename($categoryName, $categorySlug);

        $this->repository->update($category);

        return $category;
    }

    public function getCategory(string $id): ?Category
    {
        $categoryId = new CategoryId($id);
        return $this->repository->find($categoryId);
    }

    public function getCategoryWithSkills(string $id): ?Category
    {
        $categoryId = new CategoryId($id);
        return $this->repository->findWithSkills($categoryId);
    }

    public function getAllCategories(): array
    {
        return $this->repository->findAll();
    }

    public function deleteCategory(string $id): void
    {
        $categoryId = new CategoryId($id);

        if (!$this->repository->exists($categoryId)) {
            throw new DomainException('Category not found');
        }

        $this->repository->delete($categoryId);
    }
}

