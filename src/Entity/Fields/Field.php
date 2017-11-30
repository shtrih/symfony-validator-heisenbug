<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity\Fields;

use App\Entity\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Field extends Entity
{
    /**
     * @Assert\NotBlank()
     * @App\Validator\Constraints\ValueByType(
     *     types = {
     *          "side_company_inn" = {
     *              @Assert\Valid,
     *              @Assert\Type( "App\Entity\Fields\CompanyInn" )
     *          },
     *          "resolution" = {
     *              @Assert\Valid,
     *              @Assert\Type( "App\Entity\Fields\Resolution" )
     *          },
     *          "phone" = {
     *              @Assert\Valid,
     *              @Assert\Type( "App\Entity\Fields\Phone" )
     *          }
     *     }
     * )
     */
    public $type;

    /**
     * @Assert\NotBlank()
     */
    public $value;

    /**
     * @Assert\NotBlank()
     */
    public $modifier;
}