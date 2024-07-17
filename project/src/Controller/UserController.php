<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Http\Authentication\AuthenticationSuccessHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[Route('/api/users', format: 'json')]
class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepo,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasherPassword,
        private AuthenticationSuccessHandler $successHandler
    ) {
    }

    #[Route('', name: 'app_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepo->findAll();

        return $this->json(['users' => $users], context: ['groups' => ['user:read']]);
    }

    #[Route('/{id}', name: 'app_users_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepo->find($id);

        if (null === $user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        return $this->json(['user' => $user], context: ['groups' => ['user:read']]);
    }

    #[Route('', name: 'app_users_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $emilExists = $this->userRepo->findOneBy(['email' => $request->getPayload()->get('email')]);
        $usernameExists = $this->userRepo->findOneBy(['username' => $request->getPayload()->get('username')]);

        if ($emilExists !== null || $usernameExists !== null) {
            return $this->json(['message' => 'This email or username already exists'], 400);
        }

        $user = new User();
        $user->setEmail($request->getPayload()->get('email'));
        $user->setUsername($request->getPayload()->get('username'));
        $user->setPhone($request->getPayload()->get('phone'));
        $user->setQrCode(base64_encode($user->getUsername()));
        $hashedPassword = $this->hasherPassword->hashPassword($user, $request->getPayload()->get('password'));
        $user->setPassword($hashedPassword);

        $this->em->persist($user);
        $this->em->flush();

        $auth = $this->successHandler->handleAuthenticationSuccess($user);
        $auth = json_decode($auth->getContent(), true);

        return $this->json(['user' => $user, 'auth' => $auth], context: ['groups' => ['user:read']]);
    }

    #[Route('/{id}', name: 'app_users_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepo->find($id);

        if (null === $user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        if ($request->getPayload()->has('email')) {
            $user->setEmail($request->getPayload()->get('email'));
        } else if ($request->getPayload()->has('username')) {
            $user->setUsername($request->getPayload()->get('username'));
            $user->setQrCode(base64_encode($user->getUsername()));
        } else if ($request->getPayload()->has('phone')) {
            $user->setUsername($request->getPayload()->get('phone'));
        } else if ($request->getPayload()->has('password')) {
            $hashedPassword = $this->hasherPassword->hashPassword($user, $request->getPayload()->get('password'));
            $user->setPassword($hashedPassword);
        }

        $this->em->flush();

        return $this->json(['user' => $user], context: ['groups' => ['user:read']]);
    }

    #[Route('/{id}', name: 'app_users_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepo->find($id);

        if (null === $user) {
            return $this->json(['message' => 'User not found'], 404);
        }

        $this->em->remove($user);
        $this->em->flush();

        return $this->json([]);
    }
}
