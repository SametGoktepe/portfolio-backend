<?php

namespace Src\Domain\Education\Services;

use Src\Domain\Education\Models\Education;
use Src\Domain\Education\Models\EducationId;
use Src\Domain\Education\ValueObjects\School;
use Src\Domain\Education\ValueObjects\Degree;
use Src\Domain\Education\ValueObjects\FieldOfStudy;
use Src\Domain\Education\ValueObjects\YearPeriod;
use Src\Domain\Education\Repositories\EducationRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class EducationService
{
    private EducationRepositoryInterface $repository;

    public function __construct(EducationRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createEducation(array $data): Education
    {
        $education = Education::create(
            EducationId::generate(),
            new School($data['school']),
            new Degree($data['degree']),
            new FieldOfStudy($data['field_of_study'] ?? null),
            new YearPeriod(
                $data['start_year'] ?? null,
                $data['end_year'] ?? null
            )
        );

        $this->repository->save($education);

        return $education;
    }

    public function updateEducation(string $id, array $data): Education
    {
        $educationId = new EducationId($id);
        $education = $this->repository->find($educationId);

        if (!$education) {
            throw new DomainException('Education record not found');
        }

        $education->update(
            new School($data['school']),
            new Degree($data['degree']),
            new FieldOfStudy($data['field_of_study'] ?? null),
            new YearPeriod(
                $data['start_year'] ?? null,
                $data['end_year'] ?? null
            )
        );

        $this->repository->update($education);

        return $education;
    }

    public function getEducation(string $id): ?Education
    {
        $educationId = new EducationId($id);
        return $this->repository->find($educationId);
    }

    public function getAllEducation(): array
    {
        return $this->repository->findAll();
    }

    public function deleteEducation(string $id): void
    {
        $educationId = new EducationId($id);

        if (!$this->repository->exists($educationId)) {
            throw new DomainException('Education record not found');
        }

        $this->repository->delete($educationId);
    }
}

