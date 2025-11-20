<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\WorkLife\Models\WorkLife;
use Src\Domain\WorkLife\Models\WorkLifeId;
use Src\Domain\WorkLife\ValueObjects\Company;
use Src\Domain\WorkLife\ValueObjects\Position;
use Src\Domain\WorkLife\ValueObjects\WorkPeriod;
use Src\Domain\WorkLife\ValueObjects\WorkDescription;
use Src\Domain\WorkLife\Repositories\WorkLifeRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentWorkLife;

final class EloquentWorkLifeRepository implements WorkLifeRepositoryInterface
{
    public function find(WorkLifeId $id): ?WorkLife
    {
        $eloquentWorkLife = EloquentWorkLife::find($id->value());

        if (!$eloquentWorkLife) {
            return null;
        }

        return $this->toDomain($eloquentWorkLife);
    }

    public function findAll(): array
    {
        $eloquentWorkLives = EloquentWorkLife::orderBy('start_year', 'desc')->get();

        return $eloquentWorkLives->map(fn($workLife) => $this->toDomain($workLife))->toArray();
    }

    public function findOngoing(): array
    {
        $eloquentWorkLives = EloquentWorkLife::where('is_ongoing', true)
            ->orderBy('start_year', 'desc')
            ->get();

        return $eloquentWorkLives->map(fn($workLife) => $this->toDomain($workLife))->toArray();
    }

    public function save(WorkLife $workLife): void
    {
        $eloquentWorkLife = new EloquentWorkLife();
        
        $this->mapToEloquent($eloquentWorkLife, $workLife);
        
        $eloquentWorkLife->save();
    }

    public function update(WorkLife $workLife): void
    {
        $eloquentWorkLife = EloquentWorkLife::findOrFail($workLife->id()->value());
        
        $this->mapToEloquent($eloquentWorkLife, $workLife);
        
        $eloquentWorkLife->save();
    }

    public function delete(WorkLifeId $id): void
    {
        EloquentWorkLife::destroy($id->value());
    }

    public function exists(WorkLifeId $id): bool
    {
        return EloquentWorkLife::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentWorkLife $eloquentWorkLife): WorkLife
    {
        return new WorkLife(
            new WorkLifeId($eloquentWorkLife->id),
            new Company($eloquentWorkLife->company_name),
            new Position($eloquentWorkLife->position),
            new WorkPeriod(
                $eloquentWorkLife->start_year,
                $eloquentWorkLife->end_year,
                $eloquentWorkLife->is_ongoing
            ),
            new WorkDescription($eloquentWorkLife->description),
            new \DateTimeImmutable($eloquentWorkLife->created_at),
            new \DateTimeImmutable($eloquentWorkLife->updated_at)
        );
    }

    private function mapToEloquent(EloquentWorkLife $eloquentWorkLife, WorkLife $workLife): void
    {
        $eloquentWorkLife->id = $workLife->id()->value();
        $eloquentWorkLife->company_name = $workLife->company()->value();
        $eloquentWorkLife->position = $workLife->position()->value();
        $eloquentWorkLife->start_year = $workLife->workPeriod()->startYear();
        $eloquentWorkLife->end_year = $workLife->workPeriod()->endYear();
        $eloquentWorkLife->is_ongoing = $workLife->workPeriod()->isOngoing();
        $eloquentWorkLife->description = $workLife->description()->value();
    }
}

