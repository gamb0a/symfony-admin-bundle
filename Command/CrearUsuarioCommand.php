<?php

namespace Gamboa\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraint;
use Gamboa\AdminBundle\Helper\Format;
use Gamboa\AdminBundle\Service\UserService;
use Gamboa\AdminBundle\Constraint\Rut;

class CrearUsuarioCommand extends Command
{
    protected static $defaultName = 'admin:crear-usuario';

    private $validator;
    private $userManager;

    public function __construct(UserService $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    protected function configure()
    {
        $this->setDescription('Crea un nuevo usuario para el Administrador, con todas las acciones habilitadas');
    }

    private function validate($value, Constraint $constraint) {
        $errors = $this->validator->validate($value, $constraint);
        if (count($errors) > 0)
            throw new \Exception($errors[0]->getMessage());
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->validator = Validation::createValidator();

        $helper = $this->getHelper('question');
        $nameQuestion = new Question('Ingresar Nombre Completo: ', null);
        $nameQuestion->setValidator(function ($value) {
            $this->validate($value, new Assert\NotNull());
            $this->validate($value, new Assert\NotBlank());
            $this->validate($value, new Assert\Length(["min" => 2]));
            return trim($value);
        });
        
        $emailQuestion = new Question('Ingresar Email: ', null);
        $emailQuestion->setValidator(function ($value) {
            $this->validate($value, new Assert\NotNull());
            $this->validate($value, new Assert\NotBlank());
            $this->validate($value, new Assert\Email());
            return trim($value);
        });
        
        $rutQuestion = new Question('Ingresar Rut (1234567-0): ', null);
        $rutQuestion->setValidator(function ($value) {
            $this->validate($value, new Assert\NotNull());
            $this->validate($value, new Assert\NotBlank());
            $this->validate($value, new Rut(Format::RUT_NO_DOTS));
            return trim($value);
        });
        
        $passQuestion = new Question('Ingresar Contraseña: ', null);
        $passQuestion->setHidden(true);
        $passQuestion->setHiddenFallback(false);
        $passQuestion->setValidator(function ($value) {
            $this->validate($value, new Assert\NotNull());
            $this->validate($value, new Assert\NotBlank());
            $this->validate($value, new Assert\Length(["min" => 8]));
            return trim($value);
        });
        
        $output->writeln('');
        $output->writeln('Crear Usuario');
        $output->writeln('=============');
        
        $name = $helper->ask($input, $output, $nameQuestion);
        $email = $helper->ask($input, $output, $emailQuestion);
        $rut = $helper->ask($input, $output, $rutQuestion);
        $password = $helper->ask($input, $output, $passQuestion);
        
        $repeatPassQuestion = new Question('Repetir Contraseña: ', null);
        $repeatPassQuestion->setValidator(function ($value) use ($password) {
            $this->validate($value, new Assert\NotNull());
            $this->validate($value, new Assert\NotBlank());
            $this->validate($value, new Assert\EqualTo(["value" => $password, "message" => "La contraseña no coincide"]));
            return $value;
        });
        $repeatPassQuestion->setHidden(true);
        $repeatPassQuestion->setHiddenFallback(false);
        $passwordRepeat = $helper->ask($input, $output, $repeatPassQuestion);
        
        $output->writeln('');
        $output->writeln('Se creará el siguiente usuario');
        $output->writeln('==============================');
        $output->writeln("Nombre Completo: $name");
        $output->writeln("Email: $email");
        $output->writeln("Rut: $rut");
        $output->writeln("Contraseña: ********");
        
        $confirmarQuestion = new ConfirmationQuestion('Desea continuar (y/n)?', false, '/^(y|s)/i');
        $confirma = $helper->ask($input, $output, $confirmarQuestion);
        
        if ($confirma) {
            list($rutN, $dv) = explode("-", $rut);
            $rutN = intval(str_replace(".", "", $rutN));
            $this->userManager->add($rutN, $dv, $name, null, $email, $password, true);
            $output->writeln('Se creó el usuario con éxito');
            return;
        }

        $output->writeln('<error>Canceló el ingreso</error>');
    }
}