<?php

namespace Gamboa\AdminBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class NotAuthenticated extends ConfigurationAnnotation
{

    /**
     * @return string
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'not_authenticated';
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