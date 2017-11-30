<?php
/**
 *
 * @author d.morozov
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValueByType extends Constraint
{
    public $types;

    public function getDefaultOption()
    {
        return ['types'];
    }

    public function getRequiredOptions()
    {
        return ['types'];
    }
}