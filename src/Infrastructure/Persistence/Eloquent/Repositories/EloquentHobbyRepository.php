<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\Hobby\Models\Hobby;
use Src\Domain\Hobby\Models\HobbyId;
use Src\Domain\Hobby\ValueObjects\HobbyName;
use Src\Domain\Hobby\ValueObjects\HobbyImage;
use Src\Domain\Hobby\ValueObjects\HobbyDescription;
use Src\Domain\Hobby\Repositories\HobbyRepositoryInterface;
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentHobby;

final class EloquentHobbyRepository implements HobbyRepositoryInterface
{
    public function find(HobbyId $id): ?Hobby
    {
        $eloquentHobby = EloquentHobby::find($id->value());

        if (!$eloquentHobby) {
            return null;
        }

        return $this->toDomain($eloquentHobby);
    }

    public function findAll(): array
    {
        $eloquentHobbies = EloquentHobby::orderBy('created_at', 'desc')->get();

        return $eloquentHobbies->map(fn($hobby) => $this->toDomain($hobby))->toArray();
    }

    public function save(Hobby $hobby): void
    {
        $eloquentHobby = new EloquentHobby();
        
        $this->mapToEloquent($eloquentHobby, $hobby);
        
        $eloquentHobby->save();
    }

    public function update(Hobby $hobby): void
    {
        $eloquentHobby = EloquentHobby::findOrFail($hobby->id()->value());
        
        $this->mapToEloquent($eloquentHobby, $hobby);
        
        $eloquentHobby->save();
    }

    public function delete(HobbyId $id): void
    {
        EloquentHobby::destroy($id->value());
    }

    public function exists(HobbyId $id): bool
    {
        return EloquentHobby::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentHobby $eloquentHobby): Hobby
    {
        return new Hobby(
            new HobbyId($eloquentHobby->id),
            new HobbyName($eloquentHobby->name),
            new HobbyImage($eloquentHobby->image),
            new HobbyDescription($eloquentHobby->description),
            new \DateTimeImmutable($eloquentHobby->created_at),
            new \DateTimeImmutable($eloquentHobby->updated_at)
        );
    }

    private function mapToEloquent(EloquentHobby $eloquentHobby, Hobby $hobby): void
    {
        $eloquentHobby->id = $hobby->id()->value();
        $eloquentHobby->name = $hobby->name()->value();
        $eloquentHobby->image = $hobby->image()->path();
        $eloquentHobby->description = $hobby->description()->value();
    }
}

