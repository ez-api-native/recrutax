<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CreateAdminCommand extends Command
{
    protected static $defaultName = 'app:create-admin';

    /** @var EntityManagerInterface */
    private $em;

    /** @var UserPasswordEncoderInterface */
    private $userPasswordEncoder;

    /**
     * CreateAdminCommand constructor.
     * @param EntityManagerInterface $em
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $this->em = $em;
        $this->userPasswordEncoder = $encoder;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('email', InputArgument::OPTIONAL, 'SUPER ADMIN email')
            ->addArgument('password', InputArgument::OPTIONAL, 'SUPER ADMIN password')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        $user = (new User())
            ->setEmail($email)
            ->setRoles(['ROLE_SUPER_ADMIN'])
        ;
        $user->setPassword($this->userPasswordEncoder->encodePassword($user, $password));

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('Welcome %s ! You are a super admin !', $user->getFirstname()));

        return 0;
    }
}
