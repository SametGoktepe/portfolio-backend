<?php

namespace Src\Domain\About\Models;

use Src\Domain\About\ValueObjects\Email;
use Src\Domain\About\ValueObjects\Phone;
use Src\Domain\About\ValueObjects\Address;
use Src\Domain\About\ValueObjects\FullName;
use Src\Domain\About\ValueObjects\Title;
use Src\Domain\About\ValueObjects\Summary;
use Src\Domain\About\ValueObjects\Image;
use Src\Domain\About\ValueObjects\SocialMedia;

final class About
{
    private AboutId $id;
    private Image $image;
    private FullName $fullName;
    private Title $title;
    private Summary $summary;
    private Email $email;
    private Phone $phone;
    private Address $address;
    private SocialMedia $socialMedia;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        AboutId $id,
        Image $image,
        FullName $fullName,
        Title $title,
        Summary $summary,
        Email $email,
        Phone $phone,
        Address $address,
        SocialMedia $socialMedia,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->image = $image;
        $this->fullName = $fullName;
        $this->title = $title;
        $this->summary = $summary;
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->socialMedia = $socialMedia;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        AboutId $id,
        Image $image,
        FullName $fullName,
        Title $title,
        Summary $summary,
        Email $email,
        Phone $phone,
        Address $address,
        SocialMedia $socialMedia
    ): self {
        $now = new \DateTimeImmutable();
        
        return new self(
            $id,
            $image,
            $fullName,
            $title,
            $summary,
            $email,
            $phone,
            $address,
            $socialMedia,
            $now,
            $now
        );
    }

    public function updatePersonalInfo(
        FullName $fullName,
        Title $title,
        Summary $summary
    ): void {
        $this->fullName = $fullName;
        $this->title = $title;
        $this->summary = $summary;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateContactInfo(
        Email $email,
        Phone $phone,
        Address $address
    ): void {
        $this->email = $email;
        $this->phone = $phone;
        $this->address = $address;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateSocialMedia(SocialMedia $socialMedia): void
    {
        $this->socialMedia = $socialMedia;
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function updateImage(Image $image): void
    {
        $this->image = $image;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): AboutId
    {
        return $this->id;
    }

    public function image(): Image
    {
        return $this->image;
    }

    public function fullName(): FullName
    {
        return $this->fullName;
    }

    public function title(): Title
    {
        return $this->title;
    }

    public function summary(): Summary
    {
        return $this->summary;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function phone(): Phone
    {
        return $this->phone;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function socialMedia(): SocialMedia
    {
        return $this->socialMedia;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'image' => $this->image->url(),
            'full_name' => $this->fullName->value(),
            'title' => $this->title->value(),
            'summary' => $this->summary->value(),
            'email' => $this->email->value(),
            'phone' => $this->phone->value(),
            'address' => $this->address->toArray(),
            'social_media' => $this->socialMedia->toArray(),
        ];
    }
}