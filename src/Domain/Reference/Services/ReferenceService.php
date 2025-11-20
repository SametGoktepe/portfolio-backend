<?php

namespace Src\Domain\Reference\Services;

use Src\Domain\Reference\Models\Reference;
use Src\Domain\Reference\Models\ReferenceId;
use Src\Domain\Reference\ValueObjects\FullName;
use Src\Domain\Reference\ValueObjects\ContactInfo;
use Src\Domain\Reference\ValueObjects\CompanyInfo;
use Src\Domain\Reference\ValueObjects\Quote;
use Src\Domain\Reference\ValueObjects\ProfileImage;
use Src\Domain\Reference\Repositories\ReferenceRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class ReferenceService
{
    private ReferenceRepositoryInterface $repository;

    public function __construct(ReferenceRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createReference(array $data): Reference
    {
        $reference = Reference::create(
            ReferenceId::generate(),
            new FullName($data['full_name']),
            new ContactInfo(
                $data['email'] ?? null,
                $data['phone'] ?? null
            ),
            new CompanyInfo(
                $data['company'] ?? null,
                $data['position'] ?? null
            ),
            new Quote($data['quote'] ?? null),
            new ProfileImage($data['image'] ?? null)
        );

        $this->repository->save($reference);

        return $reference;
    }

    public function updateReference(string $id, array $data): Reference
    {
        $referenceId = new ReferenceId($id);
        $reference = $this->repository->find($referenceId);

        if (!$reference) {
            throw new DomainException('Reference not found');
        }

        $reference->update(
            new FullName($data['full_name']),
            new ContactInfo(
                $data['email'] ?? null,
                $data['phone'] ?? null
            ),
            new CompanyInfo(
                $data['company'] ?? null,
                $data['position'] ?? null
            ),
            new Quote($data['quote'] ?? null),
            new ProfileImage($data['image'] ?? null)
        );

        $this->repository->update($reference);

        return $reference;
    }

    public function getReference(string $id): ?Reference
    {
        $referenceId = new ReferenceId($id);
        return $this->repository->find($referenceId);
    }

    public function getAllReferences(): array
    {
        return $this->repository->findAll();
    }

    public function deleteReference(string $id): void
    {
        $referenceId = new ReferenceId($id);

        if (!$this->repository->exists($referenceId)) {
            throw new DomainException('Reference not found');
        }

        $this->repository->delete($referenceId);
    }
}

