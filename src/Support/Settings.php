<?php

namespace CodeDistortion\Adapt\Support;

use CodeDistortion\Adapt\Adapters\LaravelMySQL\LaravelMySQLSnapshot;
use CodeDistortion\Adapt\DTO\ResolvedSettingsDTO;

/**
 * Common Adapt settings.
 */
class Settings
{
    /** @var string The name of the Adapt config file. */
    const LARAVEL_CONFIG_NAME = 'code_distortion.adapt';

    /** @var string The .env.testing file to use. */
    const LARAVEL_ENV_TESTING_FILE = '.env.testing';

    /** @var string The test-class method that can be added to define a custom way of building the databases. */
    const LARAVEL_CUSTOM_BUILD_METHOD = 'databaseInit';

    /** @var string The prefix common for all Adapt tables. */
    const ADAPT_TABLE_PREFIX = '____adapt_';

    /** @var integer The number of seconds grace-period before stale databases & snapshot files are to be deleted. */
    const DEFAULT_STALE_GRACE_SECONDS = 14400; // 4 hours



    /** @var string The table that contains Adapt's re-use meta-information. */
    const REUSE_TABLE = self::ADAPT_TABLE_PREFIX . '___';

    /** @var string A version representing the way the reuse-table is structured and used. */
    const REUSE_TABLE_VERSION = '5';



    /** @var string The journal table containing details about each table. */
    const VERIFICATION_TABLE = self::ADAPT_TABLE_PREFIX . 'verification____';



    /** @var string The path that browsers connect to initially (when browser testing) so cookies can then be set. */
    const INITIAL_BROWSER_COOKIE_REQUEST_PATH = '/____adapt____/cookie';



    /**
     * Included when preparing a database remotely between Adapt installations. Mismatches cause an exception.
     *
     * @var integer
     */
    const CONFIG_DTO_VERSION = 2;

    /** @var integer Included in the remote-share payload between Adapt installations. Mismatches cause an exception. */
    const REMOTE_SHARE_DTO_VERSION = 2;

    /** @var string The cookie/http-header used to pass the remote-share date between Adapt installations. */
    const REMOTE_SHARE_KEY = 'adapt-remote-share';

    /** @var string The path used by Adapt when instructing another installation of Adapt to build a database. */
    const REMOTE_BUILD_REQUEST_PATH = '/____adapt____/remote-build';

    /** @var string The name of the singleton that's registered with Laravel, contains the connection database list. */
    const REMOTE_SHARE_CONNECTIONS_SINGLETON_NAME = 'adapt-connection-dbs';



    /** @var string The journal table keeping track of which tables have changed. */
    const JOURNAL_CHANGE_TRACKER_TABLE = self::ADAPT_TABLE_PREFIX . 'journal_change_tracker____';

    /** @var string The prefix to use for journal tables. */
    const JOURNAL_TABLE_PREFIX = self::ADAPT_TABLE_PREFIX . 'journal_';

    /** @var string The prefix to use for journal triggers. */
    const JOURNAL_TRIGGER_PREFIX = self::ADAPT_TABLE_PREFIX . 'journal_trigger_';



    /**
     * A place for BootTestAbstract's first-test flag, which can't have its own
     * (it's not shared between the test-classes the trait is included in).
     *
     * @var boolean
     */
    public static $isFirstTest = true;

    /** @var ResolvedSettingsDTO[] The ResolvedSettingsDTOs from recently built databases. */
    public static $resolvedSettingsDTOs = [];





    /**
     * Reset anything that should be reset between internal tests of the Adapt package.
     *
     * @return void
     */
    public static function resetStaticProps()
    {
        static::$isFirstTest = true;
        Settings::$resolvedSettingsDTOs = [];
        Hasher::resetStaticProps();
        LaravelMySQLSnapshot::resetStaticProps();
    }



    /**
     * Get a recently resolved ResolvedSettingsDTO.
     *
     * @param string $currentScenarioHash The scenario-hash to return for.
     * @return ResolvedSettingsDTO|null
     */
    public static function getResolvedSettingsDTO($currentScenarioHash)
    {
        return Settings::$resolvedSettingsDTOs[$currentScenarioHash] ?? null;
    }

    /**
     * Store recently resolved ResolvedSettingsDTO for reference later.
     *
     * @param string              $currentScenarioHash The scenario-hash to store this against.
     * @param ResolvedSettingsDTO $resolvedSettingsDTO A recently resolved ResolvedSettingsDTO.
     * @return void
     */
    public static function storeResolvedSettingsDTO(
        $currentScenarioHash,
        $resolvedSettingsDTO
    ) {
        Settings::$resolvedSettingsDTOs[$currentScenarioHash] = $resolvedSettingsDTO;
    }
}
