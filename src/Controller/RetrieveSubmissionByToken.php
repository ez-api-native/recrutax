<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Repository\SubmissionRepository;

final class RetrieveSubmissionByToken
{


    private $submissionRepository;

    public function __construct(SubmissionRepository $submissionRepository)
    {
        $this->submissionRepository = $submissionRepository;
    }

    public function __invoke(string $token)
    {

            $submission = $this->submissionRepository->findOneBy(['token' => $token]);

            if(is_null($submission)){
                throw new \Exception("This offer can't be found.");
            }

            return $submission;

    }
}
