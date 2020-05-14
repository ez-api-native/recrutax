<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\Mailer;

final class CheckTokenPasswordReset
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
            $token = $data['token'];

            $user = $this->userRepository->findOneBy(['token' => $token]);

            if(is_null($user)){
                throw new \Exception("This token can't be found.");
            }

            $diff= time() - $user->getTokenCreatedAt();

            if($diff > 21000){
                throw new \Exception("Expired token.");
            }

            $user->setTokenCreatedAt(null);
            $user->setToken(null);

            $entityManager->persist($user);
            $entityManager->flush();

            return $user;

    }
}
