<?php declare(strict_types=1);

namespace App\ValueObjects\Validator\Proof;

use App\Repository\ProofRepository;

final class ProofValidator
{
    private ProofRepository $proofRepository;

    public function __construct(ProofRepository $proofRepository)
    {
        $this->proofRepository = $proofRepository;
    }

    public function validate(array $proofUrls): void
    {
        $existingProofs = $this->proofRepository->getProofsFromUrls($proofUrls);
        if (1 === count($existingProofs)) {
            throw new ProofAlreadyExistsException($existingProofs[0]);
        }
        if (1 < count($existingProofs)) {
            throw new ProofsAlreadyExistException($existingProofs);
        }
    }
}
