<?php declare(strict_types=1);

namespace App\ValueObjects\Validator\Proof;

use RuntimeException;

final class ProofAlreadyExistsException extends RuntimeException
{
    public function __construct(string $existingProofs)
    {
        parent::__construct(sprintf('Proof "%s" already exists', $existingProofs));
    }
}
