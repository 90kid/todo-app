<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('api', name: 'api_')]
class AuthController extends EntityController
{
    public function __construct(
        ValidatorInterface $validator,
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct($validator);
    }

    #[Route(
        '/login',
        name: 'login',
        methods: ['POST'],
    )]
    public function login(Request $request): JsonResponse
    {
        // TODO
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthController.php',
        ]);
    }

    #[Route(
        '/logout',
        name: 'logout',
        methods: ['POST'],
    )]
    public function logout(): JsonResponse
    {
        // TODO
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/AuthController.php',
        ]);
    }

    #[Route(
        '/register',
        name: 'register',
        methods: ['POST'],
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {
        /** @var array<string, mixed> $data */
        $data = json_decode($request->getContent(), true);
        $email = strval($data['email']);
        $password = strval($data['password']);
        $user = (new User())->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password,
        );
        $user->setPassword($hashedPassword);

        return $this->handleSave($user, $this->userRepository);
    }
}
