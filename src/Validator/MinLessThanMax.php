<?php

namespace App\Validator;


use Symfony\Component\Validator\Constraint;

/**
 * Class Something
 *
 * @package App\Validator
 *
 * @Annotation
 */
class MinLessThanMax extends Constraint
{
    public $message = 'Le minimum doit être plus petit que le maximum';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    public function validatedBy()
    {
        return get_class($this) . 'Validator';
    }
}