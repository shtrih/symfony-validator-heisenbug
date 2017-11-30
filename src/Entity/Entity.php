<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity;

abstract class Entity
{
    public function __construct(array $properties = [])
    {
        foreach ($properties as $property => $value) {
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
}