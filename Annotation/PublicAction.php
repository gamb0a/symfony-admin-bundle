<?php

namespace Gamboa\AdminBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class PublicAction extends ConfigurationAnnotation
{
    /**
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'public_action';
    }

    /**
     * Only one action directive is allowed
     *
     * @return Boolean
     * @see ConfigurationInterface
     */
    public function allowArray()
    {
        return false;
    }
}
