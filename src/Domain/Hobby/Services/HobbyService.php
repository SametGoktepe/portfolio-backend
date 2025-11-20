<?php

namespace Src\Domain\Hobby\Services;

use Src\Domain\Hobby\Models\Hobby;
use Src\Domain\Hobby\Models\HobbyId;
use Src\Domain\Hobby\ValueObjects\HobbyName;
use Src\Domain\Hobby\ValueObjects\HobbyImage;
use Src\Domain\Hobby\ValueObjects\HobbyDescription;
use Src\Domain\Hobby\Repositories\HobbyRepositoryInterface;
use Src\Domain\Shared\Exceptions\DomainException;

final class HobbyService
{
    private HobbyRepositoryInterface $repository;

    public function __construct(HobbyRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createHobby(array $data): Hobby
    {
        $hobby = Hobby::create(
            HobbyId::generate(),
            new HobbyName($data['name']),
            new HobbyImage($data['image']),
            new HobbyDescription($data['description'] ?? null)
        );

        $this->repository->save($hobby);

        return $hobby;
    }

    public function updateHobby(string $id, array $data): Hobby
    {
        $hobbyId = new HobbyId($id);
        $hobby = $this->repository->find($hobbyId);

        if (!$hobby) {
            throw new DomainException('Hobby not found');
        }

        $hobby->update(
            new HobbyName($data['name']),
            new HobbyImage($data['image']),
            new HobbyDescription($data['description'] ?? null)
        );

        $this->repository->update($hobby);

        return $hobby;
    }

    public function getHobby(string $id): ?Hobby
    {
        $hobbyId = new HobbyId($id);
        return $this->repository->find($hobbyId);
    }

    public function getAllHobbies(): array
    {
        return $this->repository->findAll();
    }

    public function deleteHobby(string $id): void
    {
        $hobbyId = new HobbyId($id);
        
        if (!$this->repository->exists($hobbyId)) {
            throw new DomainException('Hobby not found');
        }

        $this->repository->delete($hobbyId);
    }
}

