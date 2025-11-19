<?php

namespace Src\Domain\About\Services;

use Src\Domain\About\Models\About;
use Src\Domain\About\Models\AboutId;
use Src\Domain\About\Repositories\AboutRepositoryInterface;
use Src\Domain\About\ValueObjects\{
    Email, Phone, Address, FullName, Title, Summary, Image, SocialMedia
};

final class AboutService
{
    private AboutRepositoryInterface $repository;

    public function __construct(AboutRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function createAbout(array $data): About
    {
        $about = About::create(
            AboutId::generate(),
            new Image($data['image'] ?? null),
            new FullName($data['full_name']),
            new Title($data['title']),
            new Summary($data['summary']),
            new Email($data['email']),
            new Phone($data['phone']),
            new Address(
                $data['city'] ?? null,
                $data['state'] ?? null,
                $data['country'] ?? null,
                $data['postal_code'] ?? null
            ),
            new SocialMedia(
                $data['github'] ?? null,
                $data['linkedin'] ?? null,
                $data['twitter'] ?? null
            )
        );

        $this->repository->save($about);

        return $about;
    }

    public function updateAbout(string $id, array $data): About
    {
        $aboutId = new AboutId($id);
        $about = $this->repository->find($aboutId);

        if (!$about) {
            throw new \DomainException('About not found');
        }

        if (isset($data['full_name']) || isset($data['title']) || isset($data['summary'])) {
            $about->updatePersonalInfo(
                new FullName($data['full_name'] ?? $about->fullName()->value()),
                new Title($data['title'] ?? $about->title()->value()),
                new Summary($data['summary'] ?? $about->summary()->value())
            );
        }

        if (isset($data['email']) || isset($data['phone']) || isset($data['posta_code'])) {
            $about->updateContactInfo(
                new Email($data['email'] ?? $about->email()->value()),
                new Phone($data['phone'] ?? $about->phone()->value()),
                new Address(
                    $data['city'] ?? $about->address()->city(),
                    $data['state'] ?? $about->address()->state(),
                    $data['country'] ?? $about->address()->country(),
                    $data['postal_code'] ?? $about->address()->postalCode()
                )
            );
        }

        if (isset($data['github']) || isset($data['linkedin']) || isset($data['twitter'])) {
            $about->updateSocialMedia(
                new SocialMedia(
                    $data['github'] ?? $about->socialMedia()->github(),
                    $data['linkedin'] ?? $about->socialMedia()->linkedin(),
                    $data['twitter'] ?? $about->socialMedia()->twitter()
                )
            );
        }

        if (isset($data['image'])) {
            $about->updateImage(new Image($data['image']));
        }

        $this->repository->update($about);

        return $about;
    }

    public function getAbout(): ?About
    {
        return $this->repository->findFirst();
    }

    public function deleteAbout(string $id): void
    {
        $aboutId = new AboutId($id);
        
        if (!$this->repository->exists($aboutId)) {
            throw new \DomainException('About not found');
        }

        $this->repository->delete($aboutId);
    }
}