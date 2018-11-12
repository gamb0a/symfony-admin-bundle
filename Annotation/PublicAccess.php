<?php

namespace Gamboa\AdminBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class PublicAccess extends ConfigurationAnnotation
{
    /**
     * @return string
     *
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'public_access';
    }

    /**
     * Only one action directive is allowed.
     *
     * @return bool
     *
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return false;
    }
}
