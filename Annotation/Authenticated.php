<?php

namespace Gamboa\AdminBundle\Annotation;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ConfigurationAnnotation;

/**
 * @Annotation
 */
class Authenticated extends ConfigurationAnnotation
{
    protected $name;
    protected $description;

    public function getAsArray()
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
        ];
    }

    public function __construct(array $data)
    {
        if (!isset($data['value']) && !isset($data['name'])) {
            throw new \LogicException(sprintf('"name" property is required on annotation %s', \get_class($this)));
        }

        if (isset($data['value'])) {
            $data['name'] = $data['value'];
            unset($data['value']);
        }

        foreach ($data as $key => $value) {
            $method = 'set'.str_replace('_', '', $key);
            if (!method_exists($this, $method)) {
                throw new \BadMethodCallException(sprintf('Unknown property "%s" on annotation "%s".', $key, \get_class($this)));
            }
            $this->$method($value);
        }
    }

    public function setName($value)
    {
        $this->name = $value;
    }

    public function setDescription($value)
    {
        $this->description = $value;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return string
     *
     * @see ConfigurationInterface
     */
    public function getAliasName()
    {
        return 'authenticated';
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
