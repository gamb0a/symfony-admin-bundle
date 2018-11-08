<?php

namespace Gamboa\AdminBundle;

use Gamboa\AdminBundle\DependencyInjection\ServicesExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class GamboaAdminBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ServicesExtension();
    }
}
