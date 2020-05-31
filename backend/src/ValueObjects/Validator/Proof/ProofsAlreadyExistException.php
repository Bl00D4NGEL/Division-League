<?php declare(strict_types=1);

namespace App\ValueObjects\Validator\Proof;

use RuntimeException;

final class ProofsAlreadyExistException extends RuntimeException
{
    /**
     * @param string[] $existingProofs
     */
    public function __construct(array $existingProofs)
    {
        parent::__construct(sprintf('Proofs "%s" already exist', implode('", "', $existingProofs)));
    }
}
