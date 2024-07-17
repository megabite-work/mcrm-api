<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;

final class JwtUserProvider implements PayloadAwareUserProviderInterface
{
    public function __construct(private UserRepository $userRepo)
    {
    }
    public function loadUserByIdentifierAndPayload(string $identifier, array $payload): UserInterface
    {
        return $this->getUser($identifier, $payload['id']);
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->getUser('username', $identifier);
    }

    public function supportsClass(string $class): bool
    {
        return $class === User::class || is_subclass_of($class, User::class);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    private function getUser($key, $value): UserInterface
    {
        $user = $this->userRepo->findOneBy([$key => $value]);
        if (null === $user) {
            $e = new UserNotFoundException('User with id ' . json_encode($value) . ' not found');
            $e->setUserIdentifier(json_decode($value));

            throw $e;
        }

        return $user;
    }
}
