<?php
/**
 *
 * @author d.morozov
 */

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Entity;
use App\Entity\Fields;
use Symfony\Component\Validator\Validation;

class ValidatorTest extends TestCase
{
    public function providerSuccess()
    {
        return [
            '1right field 1wrong field' => [
                'errors-expected' => 4,
                'fields' => [
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
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => '12345 wrong', // 1
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong', // 2
                                'name' => 'Lab',
                            ],
                            'mode' => [], // 3, 4
                        ],
                        'modifier' => 'eq',
                    ]),
                ]
            ],
            '1r 1w 1wrong-type' => [
                'errors-expected' => 5,
                'fields' => [
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
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => '12345 wrong',
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong',
                                'name' => 'Lab',
                            ],
                            'mode' => [],
                        ],
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        'type' => 'wrong type',
                        'value' => 'foo',
                        'modifier' => 'bar',
                    ]),
                ],
            ],
            '2w' => [
                'errors-expected' => 6,
                'fields' => [
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Resolution */
                        'type' => 'resolution',
                        'value' => [
                            'query'     => 'foo',
                            'proximity' => 'ipsum', // 1
                            'mode'      => 'dolor', // 2
                        ],
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => '12345 wrong',
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong',
                                'name' => 'Lab',
                            ],
                            'mode' => [],
                        ],
                        'modifier' => 'eq',
                    ]),
                ]
            ],
            '2w 2r' => [
                'errors-expected' => 5,
                'fields' => [
                    new Fields\Field([
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => '12345 wrong',
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong',
                                'name' => 'Lab',
                            ],
                            'mode' => [],
                        ],
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        'type' => 'wrong type',
                        'value' => 'foo',
                        'modifier' => 'bar',
                    ]),
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
                ],
            ],
        ];
    }

    /**
     * Кол-во фактических ошибок и выдаваемых валидатором совпадает
     *
     * @dataProvider providerSuccess
     *
     * @param $fields
     */
    public function testSuccessfulValidation($errorsCount, $fields)
    {
        $validateObject = new Entity\SearchInput([
            'conditions' => $fields
        ]);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator()
        ;
        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($validateObject);

        $this->assertEquals($errorsCount, $errors->count(), 'errors count differs');
    }

    public function providerFail()
    {
        return [
            '2r 1w' => [
                'errors-expected' => 0,
                'fields' => [
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
                            'id'   => '12345 wrong',
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong',
                                'name' => 'Lab',
                            ],
                            'mode' => [],
                        ],
                        'modifier' => 'eq',
                    ]),
                ],
            ],
            '2r 1w (different fields)' => [
                'errors-expected' => 0,
                'fields' => [
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
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => 1234567890,
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 1,
                                'name' => 'Lab',
                            ],
                            'mode' => [
                                'id' => 'default',
                                'name' => 'Default'
                            ],
                        ],
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Resolution */
                        'type' => 'resolution',
                        'value' => [
                            'query'     => 'foo',
                            'proximity' => '2',
                            'mode'      => 'wrong mode',
                        ],
                        'modifier' => 'eq',
                    ]),
                ],
            ],
            '1r 2w' => [
                'errors-expected' => 3,
                'fields' => [
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
                        /** @see \App\Entity\Fields\CompanyInn */
                        'type' => 'side_company_inn',
                        'value' => [
                            'id'   => 'wrong', // 1
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 1,
                                'name' => 'Lab',
                            ],
                            'mode' => [
                                'id' => 'wrong', // 2
                                'name' => 'Default'
                            ],
                        ],
                        'modifier' => 'wrong', // 3
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Resolution */
                        'type' => 'resolution',
                        'value' => [
                            'query'     => 'foo',
                            'proximity' => '2',
                            'mode'      => 'wrong mode', // 4
                        ],
                        'modifier' => 'wrong', // 5
                    ]),
                ],
            ],
            '2r 4w (simpler field phone)' => [
                'errors-expected' => 0,
                'fields' => [
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 42,
                        'modifier' => 'noteq',
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 1234,
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'foo0',    // 1
                        'modifier' => 'bar0', // 2
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz1',    // 3
                        'modifier' => 'lol1', // 4
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz2',    // 5
                        'modifier' => 'lol2', // 6
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz3',    // 7
                        'modifier' => 'lol3', // 8
                    ]),
                ]
            ],
            '1r 4w (simpler field phone)' => [
                'errors-expected' => 2,
                'fields' => [
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 1234,
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'foo0',    // 1
                        'modifier' => 'bar0', // 2
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz1',    // 3
                        'modifier' => 'lol1', // 4
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz2',    // 5
                        'modifier' => 'lol2', // 6
                    ]),
                    new Fields\Field([
                        /** @see \App\Entity\Fields\Phone */
                        'type' => 'phone',
                        'value' => 'baz3',    // 7
                        'modifier' => 'lol3', // 8
                    ]),
                ]
            ],
            '2r 2w' => [
                'errors-expected' => 1,
                'fields' => [
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
                            'id'   => '12345 wrong',
                            'name' => 'ACME',
                            'type' => [
                                'id'   => 'wrong',
                                'name' => 'Lab',
                            ],
                            'mode' => [],
                        ],
                        'modifier' => 'eq',
                    ]),
                    new Fields\Field([
                        'type' => 'wrong type',
                        'value' => 'foo',
                        'modifier' => 'bar',
                    ]),
                ],
            ],
        ];
    }

    /**
     * Кол-во фактических ошибок и выдаваемых валидатором не совпадает.
     * В списке есть невалидные правила, но они не валидируются, поэтому 0 ошибок.
     *
     * @dataProvider providerFail
     */
    public function testFailedValidation($errorsCount, $fields)
    {
        $validateObject = new Entity\SearchInput([
            'conditions' => $fields
        ]);

        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->getValidator()
        ;
        /** @var \Symfony\Component\Validator\ConstraintViolationList $errors */
        $errors = $validator->validate($validateObject);

        // echo $errors;

        $this->assertEquals($errorsCount, $errors->count(), 'errors count differs');
    }
}