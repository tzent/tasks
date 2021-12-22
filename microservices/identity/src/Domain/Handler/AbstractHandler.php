<?php

namespace App\Domain\Handler;

use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AbstractHandler
{
    /**
     * @var ConstraintViolationListInterface
     */
    protected ConstraintViolationListInterface $errors;

    public function __construct()
    {
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
}