<?php
/**
 *
 * @author d.morozov
 */

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ValueByTypeValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        $context = $this->context;
        $object = $context->getObject();

        $typeKey = $value;
        if (!isset($constraint->types[$typeKey])) {
            return $this->context->buildViolation("value_by_type:invalid_type")->addViolation();
        }

        if ($context instanceof ExecutionContextInterface) {
            $constraints = $constraint->types[$typeKey];

            if (!empty($fieldType = $this->getTranslateClass($constraints))) {
                $object = $this->translateValue($object, new $fieldType);
            }

            $context
                ->getValidator()
                ->inContext($context)
                ->validate($object, $constraints)
            ;
        }
        else {
            throw new \Exception("Not implemented");
        }
    }

    private function translateValue($sourceObject, $destinationObject)
    {
        foreach ($destinationObject as $propName => $value) {
            if (property_exists($sourceObject, $propName)) {
                $destinationObject->{$propName} = $sourceObject->{$propName};
            }
        }

        return $destinationObject;
    }

    /**
     * @param Constraint|Constraint[] $constraints
     * @return mixed
     */
    private function getTranslateClass($constraints)
    {
        if (!is_array($constraints) && ($constraints instanceof Type) && class_exists($constraints->type)) {
            return $constraints->type;
        }

        if (is_array($constraints)) {
            foreach ($constraints as $constraint) {
                if (($constraint instanceof Type) && class_exists($constraint->type)) {
                    return $constraint->type;
                }
            }
        }

        return false;
    }
}