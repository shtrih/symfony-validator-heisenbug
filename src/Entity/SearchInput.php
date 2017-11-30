<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class SearchInput extends Entity
{
    /**
     * @see \App\Entity\Fields\Field
     * @Assert\All(
     *  @Assert\Type( type="\App\Entity\Fields\Field" )
     * )
     */
    public $conditions;
}