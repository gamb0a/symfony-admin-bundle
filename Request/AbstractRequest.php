<?php

namespace Gamboa\AdminBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Gamboa\AdminBundle\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validation;

abstract class AbstractRequest
{
    const Required = "required";
    const Optional = "optional";

    /**
     * @var Request request The current symfony request 
     */
    private $request;

    /**
     * @var array params  The defined params
     */
    private $params;

    private $validator;

    function __construct(Request $request)
    {
        $this->request = $request;
        $this->validator = Validation::createValidator();
        $this->setParameters();
        $this->validate();
        $this->postValidation();
    }

    protected function addOptional(string $name, array $validations = [], mixed $defaultValue = null)
    {
        $this->params[$name] = [
            "type" => self::Optional,
            "validation" => $validations,
            "default" => $defaultValue,
        ];
    }

    protected function addRequired(string $name, string $message, array $validations = [])
    {
        $this->params[$name] = [
            "type" => self::Required,
            "validation" => $validations,
            "defaultMessage" => $message
        ];
    }

    public function get(string $name)
    {
        return array_key_exists($name, $this->params) ? $this->params[$name]["value"] : null;
    }

    protected function set(string $name, $value)
    {
        if (array_key_exists($name, $this->params))
            $this->params[$name]["value"] = $value;
        else
            $this->params[$name] = ["value" => $value];
    }

    protected function setParameters()
    {

    }

    private function validate()
    {
        $errorList = [];
        foreach ($this->params as $key => $param) {
            if ($param["type"] == self::Required) {
                if (!$this->request->request->has($key) && !$this->request->query->has($key)) {
                    $errorList[$key] = $param["defaultMessage"];
                } else {
                    $this->params[$key]["value"] = $this->request->get($key);
                    foreach ($param["validation"] as $validation) {
                        $errors = $this->validator->validate($this->params[$key]["value"], $validation->getConstraint());
                        if (count($errors) > 0) {
                            $errorList[$key] = $errors[0]->getMessage();
                            continue;
                        }
                    }
                }
            } else {
                if (!$this->request->request->has($key) && !$this->request->query->has($key)) {
                    $this->params[$key]["value"] = $param["default"];
                }

                foreach ($param["validation"] as $validation) {
                    $errors = $this->validator->validate($this->params[$key]["value"], $validation->getConstraint());
                    if (count($errors) > 0) {
                        $errorList[$key] = $errors[0]->getMessage();
                        continue;
                    }
                }
            }
        }

        if (count($errorList) > 0) {
            $this->throwException($errorList);
        }
    }

    protected function throwException (array $errorList) {
        throw new BadRequestHttpException($errorList);
    }

    protected function postValidation()
    {

    }
}