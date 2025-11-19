<?php

namespace Src\Infrastructure\Persistence\Eloquent\Repositories;

use Src\Domain\About\Models\About;
use Src\Domain\About\Models\AboutId;
use Src\Domain\About\Repositories\AboutRepositoryInterface;
use Src\Domain\About\ValueObjects\{
    Email, Phone, Address, FullName, Title, Summary, Image, SocialMedia
};
use Src\Infrastructure\Persistence\Eloquent\Models\EloquentAbout;

final class EloquentAboutRepository implements AboutRepositoryInterface
{
    public function find(AboutId $id): ?About
    {
        $eloquentAbout = EloquentAbout::find($id->value());

        if (!$eloquentAbout) {
            return null;
        }

        return $this->toDomain($eloquentAbout);
    }

    public function findFirst(): ?About
    {
        $eloquentAbout = EloquentAbout::first();

        if (!$eloquentAbout) {
            return null;
        }

        return $this->toDomain($eloquentAbout);
    }

    public function save(About $about): void
    {
        $eloquentAbout = new EloquentAbout();
        
        $this->mapToEloquent($eloquentAbout, $about);
        
        $eloquentAbout->save();
    }

    public function update(About $about): void
    {
        $eloquentAbout = EloquentAbout::findOrFail($about->id()->value());
        
        $this->mapToEloquent($eloquentAbout, $about);
        
        $eloquentAbout->save();
    }

    public function delete(AboutId $id): void
    {
        EloquentAbout::destroy($id->value());
    }

    public function exists(AboutId $id): bool
    {
        return EloquentAbout::where('id', $id->value())->exists();
    }

    private function toDomain(EloquentAbout $eloquentAbout): About
    {
        return new About(
            new AboutId($eloquentAbout->id),
            new Image($eloquentAbout->image),
            new FullName($eloquentAbout->full_name),
            new Title($eloquentAbout->title),
            new Summary($eloquentAbout->summary),
            new Email($eloquentAbout->email),
            new Phone($eloquentAbout->phone),
            new Address(
                $eloquentAbout->city,
                $eloquentAbout->state,
                $eloquentAbout->country,
                $eloquentAbout->postal_code ?? null,
            ),
            new SocialMedia(
                $eloquentAbout->github,
                $eloquentAbout->linkedin,
                $eloquentAbout->twitter
            ),
            new \DateTimeImmutable($eloquentAbout->created_at),
            new \DateTimeImmutable($eloquentAbout->updated_at)
        );
    }

    private function mapToEloquent(EloquentAbout $eloquentAbout, About $about): void
    {
        $eloquentAbout->id = $about->id()->value();
        $eloquentAbout->image = $about->image()->path();
        $eloquentAbout->full_name = $about->fullName()->value();
        $eloquentAbout->title = $about->title()->value();
        $eloquentAbout->summary = $about->summary()->value();
        $eloquentAbout->email = $about->email()->value();
        $eloquentAbout->phone = $about->phone()->value();
        $eloquentAbout->city = $about->address()->city();
        $eloquentAbout->state = $about->address()->state();
        $eloquentAbout->country = $about->address()->country();
        $eloquentAbout->postal_code = $about->address()->postalCode();
        $eloquentAbout->github = $about->socialMedia()->github();
        $eloquentAbout->linkedin = $about->socialMedia()->linkedin();
        $eloquentAbout->twitter = $about->socialMedia()->twitter();
    }
}