<?php

namespace App\Application\Mapper\Request;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractMapper
{
    /**
     * @var ValidatorInterface
     */
    protected ValidatorInterface $validator;

    /**
     * @var ConstraintViolationListInterface
     */
    protected ConstraintViolationListInterface $errors;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        $this->errors = new ConstraintViolationList();
    }

    /**
     * @return bool
     */
    public function hasError(): bool
    {
        return $this->errors->count() > 0;
    }

    /**
     * @return array
     */
    public function getError(): array
    {
        if ($this->hasError()) {
            $error = $this->errors->get(0);

            return [$error->getPropertyPath() => $error->getMessage()];
        }

        return [];
    }

    /**
     * @param object $object
     * @return void
     */
    protected function validate(object $object): void
    {
        $this->errors = $this->validator->validate($object);
    }
}