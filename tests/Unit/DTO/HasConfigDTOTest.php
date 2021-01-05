<?php

namespace CodeDistortion\Adapt\Tests\Unit\DTO;

use App;
use CodeDistortion\Adapt\DTO\ConfigDTO;
use CodeDistortion\Adapt\Tests\Integration\Support\DatabaseBuilderTestTrait;
use CodeDistortion\Adapt\Tests\PHPUnitTestCase;
use CodeDistortion\Adapt\Tests\Unit\DTO\Support\HasConfigDTOClass;

/**
 * Test the HasConfigDTO trait.
 *
 * @phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class HasConfigDTOTest extends PHPUnitTestCase
{
    use DatabaseBuilderTestTrait;

    /**
     * Provide data for the config_dto_can_set_and_get_values test.
     *
     * @return mixed[][]
     */
    public function configDtoDataProvider(): array
    {
        return [
            'databaseModifier' => [
                'method' => 'databaseModifier',
                'params' => ['1'],
                'outcome' => [
                    'databaseModifier' => '1',
                ],
            ],
            'noDatabaseModifier' => [
                'method' => 'noDatabaseModifier',
                'params' => [],
                'outcome' => [
                    'databaseModifier' => '',
                ],
            ],

            'preMigrationImports' => [
                'method' => 'preMigrationImports',
                'params' => [['a']],
                'outcome' => [
                    'preMigrationImports' => ['a'],
                ],
            ],
            'noPreMigrationImports' => [
                'method' => 'noPreMigrationImports',
                'params' => [],
                'outcome' => [
                    'preMigrationImports' => [],
                ],
            ],

            'migrations 1' => [
                'method' => 'migrations',
                'params' => [true],
                'outcome' => [
                    'migrations' => true,
                ],
            ],
            'migrations 2' => [
                'method' => 'migrations',
                'params' => [false],
                'outcome' => [
                    'migrations' => false,
                ],
            ],
            'migrations 3' => [
                'method' => 'migrations',
                'params' => ['a'],
                'outcome' => [
                    'migrations' => 'a',
                ],
            ],
            'noMigrations' => [
                'method' => 'noMigrations',
                'params' => [],
                'outcome' => [
                    'migrations' => false,
                ],
            ],

            'seeders' => [
                'method' => 'seeders',
                'params' => [['a']],
                'outcome' => [
                    'seeders' => ['a'],
                ],
            ],
            'noSeeders' => [
                'method' => 'noSeeders',
                'params' => [],
                'outcome' => [
                    'seeders' => [],
                ],
            ],

            'cacheTools 1' => [
                'method' => 'cacheTools',
                'params' => [true, false, false],
                'outcome' => [
                    'reuseTestDBs' => true,
                    'dynamicTestDBs' => false,
                    'transactions' => false,
                ],
            ],
            'cacheTools 2' => [
                'method' => 'cacheTools',
                'params' => [false, true, false],
                'outcome' => [
                    'reuseTestDBs' => false,
                    'dynamicTestDBs' => true,
                    'transactions' => false,
                ],
            ],
            'cacheTools 3' => [
                'method' => 'cacheTools',
                'params' => [false, false, true],
                'outcome' => [
                    'reuseTestDBs' => false,
                    'dynamicTestDBs' => false,
                    'transactions' => true,
                ],
            ],

            'reuseTestDBs 1' => [
                'method' => 'reuseTestDBs',
                'params' => [true],
                'outcome' => [
                    'reuseTestDBs' => true,
                ],
            ],
            'reuseTestDBs 2' => [
                'method' => 'reuseTestDBs',
                'params' => [false],
                'outcome' => [
                    'reuseTestDBs' => false,
                ],
            ],
            'noReuseTestDBs' => [
                'method' => 'noReuseTestDBs',
                'params' => [],
                'outcome' => [
                    'reuseTestDBs' => false,
                ],
            ],

            'dynamicTestDBs 1' => [
                'method' => 'dynamicTestDBs',
                'params' => [true],
                'outcome' => [
                    'dynamicTestDBs' => true,
                ],
            ],
            'dynamicTestDBs 2' => [
                'method' => 'dynamicTestDBs',
                'params' => [false],
                'outcome' => [
                    'dynamicTestDBs' => false,
                ],
            ],
            'noDynamicTestDBs' => [
                'method' => 'noDynamicTestDBs',
                'params' => [],
                'outcome' => [
                    'dynamicTestDBs' => false,
                ],
            ],

            'transactions 1' => [
                'method' => 'transactions',
                'params' => [true],
                'outcome' => [
                    'transactions' => true,
                ],
            ],
            'transactions 2' => [
                'method' => 'transactions',
                'params' => [false],
                'outcome' => [
                    'transactions' => false,
                ],
            ],
            'noTransactions' => [
                'method' => 'noTransactions',
                'params' => [],
                'outcome' => [
                    'transactions' => false,
                ],
            ],

            'snapshots 1' => [
                'method' => 'snapshots',
                'params' => [true, false],
                'outcome' => [
                    'snapshotsEnabled' => true,
                    'takeSnapshotAfterMigrations' => true,
                    'takeSnapshotAfterSeeders' => false,
                ],
            ],
            'snapshots 2' => [
                'method' => 'snapshots',
                'params' => [false, true],
                'outcome' => [
                    'snapshotsEnabled' => true,
                    'takeSnapshotAfterMigrations' => false,
                    'takeSnapshotAfterSeeders' => true,
                ],
            ],
            'noSnapshots' => [
                'method' => 'noSnapshots',
                'params' => [],
                'outcome' => [
                    'snapshotsEnabled' => false,
                    'takeSnapshotAfterMigrations' => false,
                    'takeSnapshotAfterSeeders' => false,
                ],
            ],

            'isBrowserTest 1' => [
                'method' => 'isBrowserTest',
                'params' => [true],
                'outcome' => [
                    'isBrowserTest' => true,
                ],
            ],
            'isBrowserTest 2' => [
                'method' => 'isBrowserTest',
                'params' => [false],
                'outcome' => [
                    'isBrowserTest' => false,
                ],
            ],
            'isNotBrowserTest' => [
                'method' => 'isNotBrowserTest',
                'params' => [],
                'outcome' => [
                    'isBrowserTest' => false,
                ],
            ],
        ];
    }

    /**
     * Test that the HasConfigDTOTrait object can set and get values properly.
     *
     * @test
     * @dataProvider configDtoDataProvider
     * @param string       $method  The set method to call.
     * @param mixed[]      $params  The parameters to pass to this set method, and the values to check after.
     * @param mixed[]|null $outcome The outcome values to check for (uses $params if not given).
     * @return void
     */
    public function has_config_dto_trait_can_set_and_get_values(
        string $method,
        array $params,
        array $outcome = null
    ) {

        $config = new ConfigDTO();
        $object = new HasConfigDTOClass($config);
        call_user_func_array([$object, $method], $params);

        foreach ($outcome as $field => $value) {
            $this->assertSame($value, $config->$field);
        }
    }

    /**
     * Test that the HasConfigDTOTrait object can set and get values properly.
     *
     * @test
     * @return void
     */
    public function has_config_dto_trait_can_get_connection()
    {
        $config = (new ConfigDTO())->connection('a');
        $object = new HasConfigDTOClass($config);
        $this->assertSame('a', $object->getConnection());
    }
}