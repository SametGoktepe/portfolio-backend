<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Education\Models\Education;
use Src\Domain\Education\Models\EducationId;
use Src\Domain\Education\ValueObjects\School;
use Src\Domain\Education\ValueObjects\Degree;
use Src\Domain\Education\ValueObjects\FieldOfStudy;
use Src\Domain\Education\ValueObjects\YearPeriod;
use Src\Domain\Education\Repositories\EducationRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentEducation;

final class EloquentEducationRepository implements EducationRepositoryInterface
{
    public function find(EducationId $id): ?Education
    {
        $eloquentEducation = EloquentEducation::find($id->value());

        if (!$eloquentEducation) {
            return null;
        }

        return $this->toDomain($eloquentEducation);
    }

    public function findAll(): array
    {
        $eloquentEducations = EloquentEducation::orderBy('start_year', 'desc')->get();

        return $eloquentEducations->map(fn($education) => $this->toDomain($education))->toArray();
    }

    public function save(Education $education): void
    {
        $eloquentEducation = new EloquentEducation();
        
        $this->mapToEloquent($eloquentEducation, $education);
        
        $eloquentEducation->save();
    }

    public function update(Education $education): void
    {
        $eloquentEducation = EloquentEducation::findOrFail($education->id()->value());
        
        $this->mapToEloquent($eloquentEducation, $education);
        
        $eloquentEducation->save();
    }

    public function delete(EducationId $id): void
    {
        EloquentEducation::destroy($id->value());
    }

    public function exists(EducationId $id): bool
    {
        return EloquentEducation::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentEducation $eloquentEducation): Education
    {
        return new Education(
            new EducationId($eloquentEducation->id),
            new School($eloquentEducation->school),
            new Degree($eloquentEducation->degree),
            new FieldOfStudy($eloquentEducation->field_of_study),
            new YearPeriod(
                $eloquentEducation->start_year,
                $eloquentEducation->end_year
            ),
            new \DateTimeImmutable($eloquentEducation->created_at),
            new \DateTimeImmutable($eloquentEducation->updated_at)
        );
    }

    private function mapToEloquent(EloquentEducation $eloquentEducation, Education $education): void
    {
        $eloquentEducation->id = $education->id()->value();
        $eloquentEducation->school = $education->school()->value();
        $eloquentEducation->degree = $education->degree()->value();
        $eloquentEducation->field_of_study = $education->fieldOfStudy()->value();
        $eloquentEducation->start_year = $education->yearPeriod()->startYear();
        $eloquentEducation->end_year = $education->yearPeriod()->endYear();
    }
}

