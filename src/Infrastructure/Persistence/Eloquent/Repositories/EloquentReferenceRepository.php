<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Reference\Models\Reference;
use Src\Domain\Reference\Models\ReferenceId;
use Src\Domain\Reference\ValueObjects\FullName;
use Src\Domain\Reference\ValueObjects\ContactInfo;
use Src\Domain\Reference\ValueObjects\CompanyInfo;
use Src\Domain\Reference\ValueObjects\Quote;
use Src\Domain\Reference\ValueObjects\ProfileImage;
use Src\Domain\Reference\Repositories\ReferenceRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentReference;

final class EloquentReferenceRepository implements ReferenceRepositoryInterface
{
    public function find(ReferenceId $id): ?Reference
    {
        $eloquentReference = EloquentReference::find($id->value());

        if (!$eloquentReference) {
            return null;
        }

        return $this->toDomain($eloquentReference);
    }

    public function findAll(): array
    {
        $eloquentReferences = EloquentReference::orderBy('created_at', 'desc')->get();

        return $eloquentReferences->map(fn($reference) => $this->toDomain($reference))->toArray();
    }

    public function save(Reference $reference): void
    {
        $eloquentReference = new EloquentReference();

        $this->mapToEloquent($eloquentReference, $reference);

        $eloquentReference->save();
    }

    public function update(Reference $reference): void
    {
        $eloquentReference = EloquentReference::findOrFail($reference->id()->value());

        $this->mapToEloquent($eloquentReference, $reference);

        $eloquentReference->save();
    }

    public function delete(ReferenceId $id): void
    {
        EloquentReference::destroy($id->value());
    }

    public function exists(ReferenceId $id): bool
    {
        return EloquentReference::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentReference $eloquentReference): Reference
    {
        return new Reference(
            new ReferenceId($eloquentReference->id),
            new FullName($eloquentReference->full_name),
            new ContactInfo(
                $eloquentReference->email,
                $eloquentReference->phone
            ),
            new CompanyInfo(
                $eloquentReference->company,
                $eloquentReference->position
            ),
            new Quote($eloquentReference->quote),
            new ProfileImage($eloquentReference->image),
            new \DateTimeImmutable($eloquentReference->created_at),
            new \DateTimeImmutable($eloquentReference->updated_at)
        );
    }

    private function mapToEloquent(EloquentReference $eloquentReference, Reference $reference): void
    {
        $eloquentReference->id = $reference->id()->value();
        $eloquentReference->full_name = $reference->fullName()->value();
        $eloquentReference->email = $reference->contactInfo()->email();
        $eloquentReference->phone = $reference->contactInfo()->phone();
        $eloquentReference->company = $reference->companyInfo()->company();
        $eloquentReference->position = $reference->companyInfo()->position();
        $eloquentReference->quote = $reference->quote()->value();
        $eloquentReference->image = $reference->image()->path();
    }
}

