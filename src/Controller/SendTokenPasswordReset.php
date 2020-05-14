<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Mailer;

final class SendTokenPasswordReset
{


    private $userRepository;
    private $mailer;

    public function __construct(UserRepository $userRepository, Mailer $mailer)
    {
        $this->userRepository = $userRepository;
        $this->mailer = $mailer;
    }

    public function __invoke(Request $request, EntityManagerInterface $entityManager)
    {

            $data = json_decode($request->getContent(), true);
            $email = $data['email'];

            $user = $this->userRepository->findOneBy(['email' => $email]);

            if(is_null($user)){
                throw new \Exception("The user can't be found.");
            }

            $user->generateToken();

            $entityManager->persist($user);
            $entityManager->flush();

            $this->mailer
                ->sendMail(
                    $user->getEmail(),
                    'resetPassword',
                    ['token' => $user->getToken()]
                );

            return $user;


    }
}
