<?php
/**
 *
 * @author d.morozov
 */

require __DIR__.'/src/bootstrap.php';

use App\Entity;
use App\Entity\Fields;

$validateObject = new Entity\SearchInput([
    'conditions' => [
        new Fields\Field([
            /** @see \App\Entity\Fields\Resolution */
            'type' => 'resolution',
            'value' => [
                'query'     => 'foo',
                'proximity' => '2',
                'mode'      => 'default',
            ],
            'modifier' => 'eq',
        ]),
        new Fields\Field([
            'type' => 'resolution',
            'value' => [
                'query'     => 'bar',
                'proximity' => '4',
                'mode'      => 'default',
            ],
            'modifier' => 'noteq',
        ]),
        new Fields\Field([
            /** @see \App\Entity\Fields\CompanyInn */
            'type' => 'side_company_inn',
            'value' => [
                'id'   => '12345 wrong', // 1
                'name' => 'ACME',
                'type' => [
                    'id'   => 'wrong', // 2
                    'name' => 'Lab',
                ],
                'mode' => [
                    // id…, 3
                    // name…, 4
                ],
            ],
            'modifier' => 'eq',
        ]),
    ]
]);

$validator = \Symfony\Component\Validator\Validation::createValidatorBuilder()
    ->enableAnnotationMapping()
    ->getValidator()
;
/** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
$errors = $validator->validate($validateObject);

echo $errors->count() ? 'Normal.' : 'Bug found!', PHP_EOL;
echo 'Errors count: ', $errors->count(), PHP_EOL;
echo 'Text: ', $errors, PHP_EOL;