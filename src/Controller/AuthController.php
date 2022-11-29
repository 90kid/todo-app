<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Validator\ContainsDigit;
use App\Validator\ContainsSpecialCharacter;
use App\Validator\MixedCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
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
        $errors = $this->validator->validate($password, [
            new Length(['min' => 8, 'max' => 255]),
            new ContainsDigit(true),
            new ContainsSpecialCharacter(true),
            new MixedCase(true),
        ]);
        if (count($errors) > 0) {
            return $this->createAndHandleView($errors, Response::HTTP_BAD_REQUEST);
        }

        $user = (new User())->setEmail($email);
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $password,
        );
        $user->setPassword($hashedPassword);

        return $this->handleSave($user, $this->userRepository);
    }
}
