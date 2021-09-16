<?php

namespace App\Command;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Command\UserPasswordEncoderCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class InstallCommand extends Command
{
    protected static $defaultName = "app:install";

    private $container;
    private $em;
    private $encoder;

    public function __construct(ContainerInterface $container)
    {
        parent::__construct();
        $this->container = $container;
        $this->em = $container->get('doctrine')->getManager();
        $this->encoder = $container->get('security.password_encoder');
    }

    protected function configure()
    {
        $this
            ->setDescription("Procedural Installer")
            ->setHelp("This command will install your Procedural App")
//            ->addArgument('User name', InputArgument::REQUIRED, 'Please enter your name')
//            ->addArgument('User surname', InputArgument::REQUIRED, 'Please enter your surname')
//            ->addArgument('User email', InputArgument::REQUIRED, 'Please enter your email')
//            ->addArgument('User password', InputArgument::REQUIRED, 'Please enter your password')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $helper = $this->getHelper('question');
        $output->writeln('<info>Welcome to Procedural, you are about to process to the install.</info>');
        $output->writeln('');
        $questionName = new Question('<question>Please enter your name</question>');
        $questionSurname = new Question('<question>Please enter your surname</question>');
        $questionEmail = new Question('<question>Please enter your email</question>');
        $questionEmail->setValidator(function ($email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException(
                    'The email doesn\'t respect the email format. '
                );
            }
            return $email;
        });
        $questionPassword = new Question('<question>Please enter your password</question>');
        $questionPassword->setHidden(true);
        $name = $helper->ask(
            $input,
            $output,
            $questionName
        );
        $surname = $helper->ask(
            $input,
            $output,
            $questionSurname
        );
        $email = $helper->ask(
            $input,
            $output,
            $questionEmail
        );
        $password = $helper->ask(
            $input,
            $output,
            $questionPassword
        );

        $migration = $this->getApplication()->find('doctrine:migration:migrate');

        $arguments = [
            "-n" => true
        ];

        $migrationArg = new ArrayInput($arguments);
        $migrationSubmit = $migration->run($migrationArg, $output);

        $date = new \DateTime();
        $user = new User();
        $user
            ->setName($name)
            ->setSurname($surname)
            ->setEmail($email)
            ->setRoles("ROLE_ADMIN")
            ->setPassword($this->encoder->encodePassword($user, $password))
            ->setApiToken(hash('sha256', ''.$user->getId().''.$date->format('Y-m-d H:i:s').''.$user->getEmail().''));

        $this->em
            ->persist($user);
        $this->em->flush();

        $output->writeln('');
        $output->writeln('Procedural is ready to use, please go to /login and use your email (' . $email . ') and your password to sign in');

        return Command::SUCCESS;
    }
}
