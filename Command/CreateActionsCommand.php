<?php

namespace Gamboa\AdminBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Gamboa\AdminBundle\Service\UserService;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Annotations\AnnotationReader;
use Gamboa\AdminBundle\Annotation\Authenticated;
use Symfony\Component\Console\Helper\Table;

class DumpActionsCommand extends Command
{
    protected static $defaultName = 'admin:create-actions';

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
        $routes = $this->router->getRouteCollection()->all();

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
        $actions = [];
        foreach ($methods as $method) {
            // Authenticated Annotations
            foreach ($annotationReader->getMethodAnnotations($method) as $currentAnnotation) {
                if ($currentAnnotation instanceof Authenticated) {
                    $actionName = $currentAnnotation->getName();
                    $actionDescription = $currentAnnotation->getDescription();
                    list($level1, $level2, $level3) = explode('.', $actionName);
                    $actions[$actionName] = [$actionName, $level1, $level2, $level3, $actionDescription];
                }
            }
        }
        
        ksort($actions);
        $table = new Table($output);
        $table->setHeaders(['Action', 'Level 1', 'Level 2', 'Level 3', 'Description']);
        $table->setRows($actions);
        $actionCount = count($actions);
        $table->render();

        // split levels

    }
}
