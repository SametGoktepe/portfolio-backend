<?php

namespace Src\Domain\Reference\Models;

use Src\Domain\Reference\ValueObjects\FullName;
use Src\Domain\Reference\ValueObjects\ContactInfo;
use Src\Domain\Reference\ValueObjects\CompanyInfo;
use Src\Domain\Reference\ValueObjects\Quote;
use Src\Domain\Reference\ValueObjects\ProfileImage;

final class Reference
{
    private ReferenceId $id;
    private FullName $fullName;
    private ContactInfo $contactInfo;
    private CompanyInfo $companyInfo;
    private Quote $quote;
    private ProfileImage $image;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(
        ReferenceId $id,
        FullName $fullName,
        ContactInfo $contactInfo,
        CompanyInfo $companyInfo,
        Quote $quote,
        ProfileImage $image,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->fullName = $fullName;
        $this->contactInfo = $contactInfo;
        $this->companyInfo = $companyInfo;
        $this->quote = $quote;
        $this->image = $image;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function create(
        ReferenceId $id,
        FullName $fullName,
        ContactInfo $contactInfo,
        CompanyInfo $companyInfo,
        Quote $quote,
        ProfileImage $image
    ): self {
        $now = new \DateTimeImmutable();

        return new self(
            $id,
            $fullName,
            $contactInfo,
            $companyInfo,
            $quote,
            $image,
            $now,
            $now
        );
    }

    public function update(
        FullName $fullName,
        ContactInfo $contactInfo,
        CompanyInfo $companyInfo,
        Quote $quote,
        ProfileImage $image
    ): void {
        $this->fullName = $fullName;
        $this->contactInfo = $contactInfo;
        $this->companyInfo = $companyInfo;
        $this->quote = $quote;
        $this->image = $image;
        $this->updatedAt = new \DateTimeImmutable();
    }

    // Getters
    public function id(): ReferenceId
    {
        return $this->id;
    }

    public function fullName(): FullName
    {
        return $this->fullName;
    }

    public function contactInfo(): ContactInfo
    {
        return $this->contactInfo;
    }

    public function companyInfo(): CompanyInfo
    {
        return $this->companyInfo;
    }

    public function quote(): Quote
    {
        return $this->quote;
    }

    public function image(): ProfileImage
    {
        return $this->image;
    }

    public function createdAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function updatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function equals(Reference $other): bool
    {
        return $this->id->equals($other->id());
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->value(),
            'full_name' => $this->fullName->value(),
            'contact' => $this->contactInfo->toArray(),
            'company' => $this->companyInfo->toArray(),
            'quote' => $this->quote->value(),
            'image' => $this->image->url(),
        ];
    }
}

