<?php

namespace Src\Domain\WorkLife\Services;

use Src\Domain\WorkLife\Models\WorkLife;
use Src\Domain\WorkLife\Models\WorkLifeId;
use Src\Domain\WorkLife\ValueObjects\Company;
use Src\Domain\WorkLife\ValueObjects\Position;
use Src\Domain\WorkLife\ValueObjects\WorkPeriod;
use Src\Domain\WorkLife\ValueObjects\WorkDescription;
use Src\Domain\WorkLife\Repositories\WorkLifeRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class WorkLifeService
{
    private WorkLifeRepositoryInterface $repository;

    public function __construct(WorkLifeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createWorkLife(array $data): WorkLife
    {
        $isOngoing = $data['is_ongoing'] ?? false;
        
        $workLife = WorkLife::create(
            WorkLifeId::generate(),
            new Company($data['company_name']),
            new Position($data['position']),
            new WorkPeriod(
                $data['start_year'],
                $data['end_year'] ?? null,
                $isOngoing
            ),
            new WorkDescription($data['description'] ?? null)
        );

        $this->repository->save($workLife);

        return $workLife;
    }

    public function updateWorkLife(string $id, array $data): WorkLife
    {
        $workLifeId = new WorkLifeId($id);
        $workLife = $this->repository->find($workLifeId);

        if (!$workLife) {
            throw new DomainException('Work experience not found');
        }

        $isOngoing = $data['is_ongoing'] ?? false;

        $workLife->update(
            new Company($data['company_name']),
            new Position($data['position']),
            new WorkPeriod(
                $data['start_year'],
                $data['end_year'] ?? null,
                $isOngoing
            ),
            new WorkDescription($data['description'] ?? null)
        );

        $this->repository->update($workLife);

        return $workLife;
    }

    public function getWorkLife(string $id): ?WorkLife
    {
        $workLifeId = new WorkLifeId($id);
        return $this->repository->find($workLifeId);
    }

    public function getAllWorkLife(): array
    {
        return $this->repository->findAll();
    }

    public function getOngoingWorkLife(): array
    {
        return $this->repository->findOngoing();
    }

    public function deleteWorkLife(string $id): void
    {
        $workLifeId = new WorkLifeId($id);
        
        if (!$this->repository->exists($workLifeId)) {
            throw new DomainException('Work experience not found');
        }

        $this->repository->delete($workLifeId);
    }
}

