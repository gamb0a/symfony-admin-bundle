<?php

namespace Gamboa\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gamboa\AdminBundle\Service\UserService;

class DumpActionsCommand extends Command
{
    protected static $defaultName = 'admin:dump-actions';

    public function __construct(UserService $userManager)
    {
        parent::__construct();
        $this->userManager = $userManager;
    }

    protected function configure()
    {
        $this->setDescription('Crea/Actualiza/Elimina todas las acciones encontradas los controladores');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
