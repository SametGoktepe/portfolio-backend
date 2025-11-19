<?php

namespace Src\Domain\About\ValueObjects;

use Src\Domain\Shared\Exceptions\DomainException;

final class Summary
{
    private string $value;
    
    private const MIN_LENGTH = 10;
    private const MAX_LENGTH = 5000;

    public function __construct(string $value)
    {
        $this->ensureIsValid($value);
        $this->value = trim($value);
    }

    private function ensureIsValid(string $value): void
    {
        $trimmedValue = trim($value);

        if (empty($trimmedValue)) {
            throw new DomainException('Summary cannot be empty');
        }

        if (strlen($trimmedValue) < self::MIN_LENGTH) {
            throw new DomainException(
                sprintf('Summary must be at least %d characters long', self::MIN_LENGTH)
            );
        }

        if (strlen($trimmedValue) > self::MAX_LENGTH) {
            throw new DomainException(
                sprintf('Summary cannot exceed %d characters', self::MAX_LENGTH)
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function excerpt(int $length = 150, string $suffix = '...'): string
    {
        if (strlen($this->value) <= $length) {
            return $this->value;
        }

        $excerpt = substr($this->value, 0, $length);
        $lastSpace = strrpos($excerpt, ' ');

        if ($lastSpace !== false) {
            $excerpt = substr($excerpt, 0, $lastSpace);
        }

        return $excerpt . $suffix;
    }

    public function wordCount(): int
    {
        return str_word_count($this->value);
    }

    public function readingTime(int $wordsPerMinute = 200): int
    {
        return (int) ceil($this->wordCount() / $wordsPerMinute);
    }

    public function html(): string
    {
        return nl2br(htmlspecialchars($this->value));
    }

    public function paragraphs(): array
    {
        return array_filter(
            array_map('trim', explode("\n\n", $this->value))
        );
    }

    public function equals(Summary $other): bool
    {
        return $this->value === $other->value();
    }

    public function __toString(): string
    {
        return $this->value;
    }
}