<?php
/**
 *
 * @author d.morozov
 */

namespace App\Entity\Fields;

use App\Entity\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class CompanyInn extends Entity
{
    /**
     * @Assert\Collection(
     *      fields = {
     *          "id"    =   @Assert\Required(@Assert\Regex( pattern = "/^(\d{10}|\d{12})$/", message="inn" )),
     *          "name"  =   @Assert\Optional(@Assert\Type( type="string" )),
     *          "address" = @Assert\Optional(@Assert\Type( type="string" )),
     *          "type"  =   @Assert\Optional(@Assert\Collection(
     *              fields = {
     *                  "id"    = @Assert\Type( type="int" ),
     *                  "name"  = @Assert\Type( type="string" )
     *              }
     *          )),
     *          "mode" = @Assert\Collection(
     *              fields = {
     *                  "id"    = @Assert\Choice({ "exactly", "default", "inn"}),
     *                  "name"  = @Assert\Type( type="string" )
     *              }
     *          )
     *      }
     * )
     */
    public $value;

    /**
     * @Assert\Choice(choices = {"eq", "noteq"})
     */
    public $modifier;
}