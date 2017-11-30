<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity\Fields;

use App\Entity\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Phone extends Entity
{
    /**
     * @Assert\Type( type="numeric" )
     */
    public $value;

    /**
     * @Assert\Choice(choices = {"eq", "noteq"})
     */
    public $modifier;
}