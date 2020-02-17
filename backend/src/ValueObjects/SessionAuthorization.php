<?php

namespace App\ValueObjects;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionAuthorization
{
    public const AUTHORIZATION_KEY = 'Authorized';
    public const USER_ID = 'USER_ID';

    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function isAuthorized(): bool {
        return $this->session->get(self::AUTHORIZATION_KEY) === true;
    }

    public function authorize(int $userId): self {
        $this->session->set(self::AUTHORIZATION_KEY, true);
        $this->session->set(self::USER_ID, $userId);

        return $this;
    }

    public function unauthorize(): self {
        $this->session->set(self::AUTHORIZATION_KEY, false);
        $this->session->set(self::USER_ID, null);

        return $this;
    }

    public function getUserId(): ?int {
        return $this->session->get(self::USER_ID);
    }
}
