<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity\Fields;

use App\Entity\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Resolution extends Entity
{
    /**
     * @Assert\Collection(
     *      fields = {
     *          "query"     = {
     *              @Assert\NotBlank(),
     *              @Assert\Type( type="string" )
     *          },
     *          "proximity" = @Assert\Type( type="numeric" ),
     *          "mode"      = @Assert\Choice(choices = {"paragraph", "sentence", "proximity", "exactly", "default"})
     *      },
     *      allowMissingFields = true
     * )
     */
    public $value;

    /**
     * @Assert\Choice(choices = {"eq", "noteq"})
     */
    public $modifier;
}