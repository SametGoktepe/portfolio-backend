<?php

namespace Src\Domain\About\Exceptions;

use Src\Domain\Shared\Exceptions\DomainException;

final class AboutException extends DomainException
{
    public static function notFound(string $id): self
    {
        return new self(
            sprintf('About with ID <%s> not found', $id),
            404
        );
    }

    public static function alreadyExists(): self
    {
        return new self('About profile already exists', 409);
    }

    public static function invalidImageFormat(string $format): self
    {
        return new self(
            sprintf('Invalid image format <%s>. Allowed formats: jpg, jpeg, png, gif', $format),
            422
        );
    }

    public static function imageSizeExceeded(int $maxSize): self
    {
        return new self(
            sprintf('Image size exceeds maximum allowed size of %d MB', $maxSize / 1048576),
            422
        );
    }

    public static function updateFailed(): self
    {
        return new self('Failed to update about information', 500);
    }

    public static function deleteFailed(): self
    {
        return new self('Failed to delete about information', 500);
    }
}