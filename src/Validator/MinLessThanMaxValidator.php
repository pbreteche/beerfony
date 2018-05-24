<?php

namespace App\Validator;


use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MinLessThanMaxValidator extends ConstraintValidator
{

    /**
     * @var PropertyAccessorInterface
     */
    private $accessor;

    public function __construct(PropertyAccessorInterface $accessor)
    {
        $this->accessor = $accessor;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $min = $this->accessor->getValue($value, 'min');
        $max = $this->accessor->getValue($value, 'max');

        if ($min > $max) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}