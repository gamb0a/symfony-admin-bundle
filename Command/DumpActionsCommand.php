<?php

namespace Gamboa\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gamboa\AdminBundle\Service\UserService;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Gamboa\AdminBundle\Annotation\Authenticated;

class DumpActionsCommand extends Command
{
    protected static $defaultName = 'admin:dump-actions';

    public function __construct(UserService $userManager, RouterInterface $router)
    {
        parent::__construct();
        $this->userManager = $userManager;
        $this->router = $router;
    }

    protected function configure()
    {
        $this->setDescription('Crea/Actualiza/Elimina todas las acciones encontradas los controladores');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Rescatando acciones desde las siguientes rutas');
        $routes = $this->router->getRouteCollection()->all();
        $output->writeln('Se rescataron '.count($routes).' rutas');

        $methods = [];
        foreach ($routes as $route) {
            $controller = $route->getDefaults()['_controller'];
            preg_match('/(.*)::(.*)/', $controller, $matches);
            if (isset($matches[1])) {
                $methods[] = new \ReflectionMethod($matches[1], $matches[2]);
            }
        }

        $annotationReader = new AnnotationReader();
        // Iterate over methods
        foreach ($methods as $method) {
            // Authenticated Annotations
            foreach ($annotationReader->getMethodAnnotations($method) as $currentAnnotation) {
                if ($currentAnnotation instanceof Authenticated) {
                    $actionName = $currentAnnotation->getName();
                    $actionDescription = $currentAnnotation->getDescription();
                    $output->writeln('Acción Encontrada: '.$actionName.' '.$actionDescription);
                }
            }
        }
    }
}
