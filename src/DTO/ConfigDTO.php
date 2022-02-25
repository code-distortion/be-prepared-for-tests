<?php

namespace CodeDistortion\Adapt\DTO;

use CodeDistortion\Adapt\DTO\Traits\DTOBuildTrait;
use CodeDistortion\Adapt\Exceptions\AdaptRemoteShareException;
use CodeDistortion\Adapt\Support\Settings;

/**
 * Resolves default setting values when needed.
 */
class ConfigDTO
{
    use DTOBuildTrait;

    /**
     * The ConfigDTO version. An exception will be thrown when there's a mis-match between installations of Adapt.
     *
     * @var integer
     */
    public int $dtoVersion;

    /** @var string The name of the current project. */
    public string $projectName;

    /** @var string The name of the current test. */
    public string $testName;


    /** @var string The database connection to prepare. */
    public string $connection;

    /** @var boolean Whether the connection exists or not (it's ok to not exist locally when the building remotely). */
    public bool $connectionExists;

    /** @var string|null The database driver to use when building the database ("mysql", "sqlite" etc). */
    public ?string $driver = null;

    /** @var string|null The name of the database to use. */
    public ?string $database = null;

    /** @var string A database name modifier (e.g. Paratest adds a TEST_TOKEN env value to make the db unique). */
    public string $databaseModifier = '';


    /** @var string The directory to store database snapshots in. */
    public string $storageDir;

    /** @var string The prefix to add to snapshot filenames. */
    public string $snapshotPrefix;

    /** @var string The prefix to add to database names. */
    public string $databasePrefix;

    /** @var boolean Turn the usage of build-hashes on or off. */
    public bool $checkForSourceChanges;

    /** @var string[] The files and directories to look through. Changes to files will invalidate the snapshots. */
    public array $hashPaths;

    /** @var string|null The build-hash if it has already been calculated - passed to remote Adapt installations. */
    public ?string $preCalculatedBuildHash;


    /** @var string[]|string[][] The files to import before the migrations are run. */
    public array $preMigrationImports;

    /** @var boolean|string Should the migrations be run? / migrations location - if not, the db will be empty. */
    public $migrations;

    /** @var string[] The seeders to run after migrating - will only be run if migrations were run. */
    public array $seeders;

    /** @var string|null The remote Adapt installation to send "build" requests to. */
    public ?string $remoteBuildUrl;

    /** @var boolean Is a browser test being run?. When true, this will turn off $reuseTestDBs and $scenarioTestDBs. */
    public bool $isBrowserTest;

    /** @var boolean Is this process building a db locally for another remote Adapt installation?. */
    public bool $isRemoteBuild;

    /**
     * The session driver being used - will throw and exception when the remote version is different to
     * $remoteCallerSessionDriver.
     *
     * @var string
     */
    public string $sessionDriver;

    /** @var string|null The session driver being used in the caller Adapt installation. */
    public ?string $remoteCallerSessionDriver;


    /** @var boolean When turned on, databases will be reused when possible instead of rebuilding them. */
    public bool $reuseTestDBs;

    /** @var boolean When turned on, dbs will be created for each scenario (based on migrations and seeders etc). */
    public bool $scenarioTestDBs;

    /** @var string|boolean Enable snapshots, and specify when to take them - when reusing the database. */
    public $useSnapshotsWhenReusingDB;

    /** @var string|boolean Enable snapshots, and specify when to take them - when NOT reusing the database. */
    public $useSnapshotsWhenNotReusingDB;

    /** @var boolean When turned on, the database will be rebuilt instead of allowing it to be reused. */
    public bool $forceRebuild;




    /** @var string The path to the "mysql" executable. */
    public string $mysqlExecutablePath;

    /** @var string The path to the "mysqldump" executable. */
    public string $mysqldumpExecutablePath;

    /** @var string The path to the "psql" executable. */
    public string $psqlExecutablePath;

    /** @var string The path to the "pg_dump" executable. */
    public string $pgDumpExecutablePath;


    /** @var integer The number of seconds grace-period before stale databases and snapshots are to be deleted. */
    public int $staleGraceSeconds = 0;



    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->version(Settings::CONFIG_DTO_VERSION);
    }



    /**
     * Set the ConfigDTO version.
     *
     * @param integer $version The ConfigDTO version.
     * @return static
     */
    public function version(int $version): self
    {
        $this->dtoVersion = $version;
        return $this;
    }

    /**
     * Set the project-name.
     *
     * @param string $projectName The name of this project.
     * @return static
     */
    public function projectName(string $projectName): self
    {
        $this->projectName = $projectName;
        return $this;
    }

    /**
     * Set the current test-name.
     *
     * @param string $testName The name of the current test.
     * @return static
     */
    public function testName(string $testName): self
    {
        $this->testName = $testName;
        return $this;
    }


    /**
     * Set the connection to prepare.
     *
     * @param string $connection The database connection to prepare.
     * @return static
     */
    public function connection(string $connection): self
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * Set the connectionExists value.
     *
     * @param boolean $connectionExists Whether the connection exists or not (it's ok to not exist locally when the
     *                                  building remotely).
     * @return static
     */
    public function connectionExists(bool $connectionExists): self
    {
        $this->connectionExists = $connectionExists;
        return $this;
    }

    /**
     * Set the database driver to use when building the database ("mysql", "sqlite" etc).
     *
     * @param string $driver The database driver to use.
     * @return static
     */
    public function driver(string $driver): self
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * Set the database to use.
     *
     * @param string|null $database The name of the database to use.
     * @return static
     */
    public function database(?string $database): self
    {
        $this->database = $database;
        return $this;
    }

    /**
     * Set the database-modifier to use (e.g. Paratest adds a TEST_TOKEN env value to make the db unique).
     *
     * @param string $databaseModifier The modifier to use.
     * @return static
     */
    public function databaseModifier(string $databaseModifier): self
    {
        $this->databaseModifier = $databaseModifier;
        return $this;
    }

    /**
     * Set the directory to store database snapshots in.
     *
     * @param string $storageDir The storage directory to use.
     * @return static
     */
    public function storageDir(string $storageDir): self
    {
        $this->storageDir = $storageDir;
        return $this;
    }

    /**
     * Set the prefix to add to snapshot filenames.
     *
     * @param string $snapshotPrefix The prefix to use.
     * @return static
     */
    public function snapshotPrefix(string $snapshotPrefix): self
    {
        $this->snapshotPrefix = $snapshotPrefix;
        return $this;
    }

    /**
     * Set the prefix to add to database names.
     *
     * @param string $databasePrefix The prefix to use.
     * @return static
     */
    public function databasePrefix(string $databasePrefix): self
    {
        $this->databasePrefix = $databasePrefix;
        return $this;
    }

    /**
     * Turn the usage of build-hashes on or off.
     *
     * @param boolean $checkForSourceChanges Whether build-hashes should be calculated or not.
     * @return static
     */
    public function checkForSourceChanges(bool $checkForSourceChanges): self
    {
        $this->checkForSourceChanges = $checkForSourceChanges;
        return $this;
    }

    /**
     * Set the list of directories that can invalidate test-databases and snapshots.
     *
     * @param string[] $hashPaths The files and directories to look through.
     * @return static
     */
    public function hashPaths(array $hashPaths): self
    {
        $this->hashPaths = $hashPaths;
        return $this;
    }

    /**
     * Set the pre-calculated build-hash - passed to remote Adapt installations.
     *
     * @param string|null $preCalculatedBuildHash The pre-calculated build-hash.
     * @return static
     */
    public function preCalculatedBuildHash(?string $preCalculatedBuildHash): self
    {
        $this->preCalculatedBuildHash = $preCalculatedBuildHash;
        return $this;
    }



    /**
     * Set the details that affect what is being built (i.e. the database-scenario).
     *
     * @param string[]|string[][] $preMigrationImports       The files to import before the migrations are run.
     * @param boolean|string      $migrations                Should the migrations be run? / the path of the migrations
     *                                                       to run.
     * @param string[]            $seeders                   The seeders to run after migrating.
     * @param string|null         $remoteBuildUrl            The remote Adapt installation to send "build" requests to.
     * @param boolean             $isBrowserTest             Is a browser test running?.
     * @param boolean             $isRemoteBuild             Is this process building a db for another Adapt
     *                                                       installation?.
     * @param string              $sessionDriver             The session driver being used.
     * @param string|null         $remoteCallerSessionDriver The session driver being used in the caller Adapt
     *                                                       installation.
     * @return static
     */
    public function buildSettings(
        array $preMigrationImports,
        $migrations,
        array $seeders,
        ?string $remoteBuildUrl,
        bool $isBrowserTest,
        bool $isRemoteBuild,
        string $sessionDriver,
        ?string $remoteCallerSessionDriver
    ): self {

        $this->preMigrationImports = $preMigrationImports;
        $this->migrations = $migrations;
        $this->seeders = $seeders;
        $this->remoteBuildUrl = $remoteBuildUrl;
        $this->isBrowserTest = $isBrowserTest;
        $this->isRemoteBuild = $isRemoteBuild;
        $this->sessionDriver = $sessionDriver;
        $this->remoteCallerSessionDriver = $remoteCallerSessionDriver;
        return $this;
    }

    /**
     * Specify the database dump files to import before migrations run.
     *
     * @param string[]|string[][] $preMigrationImports The database dump files to import, one per database type.
     * @return static
     */
    public function preMigrationImports(array $preMigrationImports): self
    {
        $this->preMigrationImports = $preMigrationImports;
        return $this;
    }

    /**
     * Turn migrations on or off, or specify the location of the migrations to run.
     *
     * @param boolean|string $migrations Should the migrations be run? / the path of the migrations to run.
     * @return static
     */
    public function migrations($migrations): self
    {
        $this->migrations = false;
        if ((is_string($migrations) && (mb_strlen($migrations))) || (is_bool($migrations))) {
            $this->migrations = $migrations;
        }
        return $this;
    }

    /**
     * Specify the seeders to run.
     *
     * @param string[] $seeders The seeders to run after migrating.
     * @return static
     */
    public function seeders(array $seeders): self
    {
        $this->seeders = $seeders;
        return $this;
    }

    /**
     * Specify the url to send "build" requests to.
     *
     * @param string|null $remoteBuildUrl The remote Adapt installation to send "build" requests to.
     * @return static
     */
    public function remoteBuildUrl(?string $remoteBuildUrl): self
    {
        $this->remoteBuildUrl = $remoteBuildUrl;
        return $this;
    }

    /**
     * Turn the is-browser-test setting on (or off).
     *
     * @param boolean $isBrowserTest Is this test a browser-test?.
     * @return static
     */
    public function isBrowserTest(bool $isBrowserTest): self
    {
        $this->isBrowserTest = $isBrowserTest;
        return $this;
    }

    /**
     * Turn the is-remote-build setting on (or off).
     *
     * @param boolean $isRemoteBuild Is this process building a db for another Adapt installation?.
     * @return static
     */
    public function isRemoteBuild(bool $isRemoteBuild): self
    {
        $this->isRemoteBuild = $isRemoteBuild;
        return $this;
    }

    /**
     * Set the session-driver.
     *
     * @param string $sessionDriver The session driver being used.
     * @return static
     */
    public function sessionDriver(string $sessionDriver): self
    {
        $this->sessionDriver = $sessionDriver;
        return $this;
    }

    /**
     * Set the caller Adapt session-driver.
     *
     * @param string|null $remoteCallerSessionDriver The session driver being used.
     * @return static
     */
    public function remoteCallerSessionDriver(?string $remoteCallerSessionDriver): self
    {
        $this->remoteCallerSessionDriver = $remoteCallerSessionDriver;
        return $this;
    }

    /**
     * Set the types of cache to use.
     *
     * @param boolean $reuseTestDBs    Reuse databases when possible (instead of rebuilding them)?.
     * @param boolean $scenarioTestDBs Create databases as needed for the database-scenario?.
     * @return static
     */
    public function cacheTools(
        bool $reuseTestDBs,
        bool $scenarioTestDBs
    ): self {
        $this->reuseTestDBs = $reuseTestDBs;
        $this->scenarioTestDBs = $scenarioTestDBs;
        return $this;
    }

    /**
     * Turn the reuse-test-dbs setting on (or off).
     *
     * @param boolean $reuseTestDBs Reuse existing databases?.
     * @return static
     */
    public function reuseTestDBs(bool $reuseTestDBs): self
    {
        $this->reuseTestDBs = $reuseTestDBs;
        return $this;
    }

    /**
     * Turn the scenario-test-dbs setting on (or off).
     *
     * @param boolean $scenarioTestDBs Create databases as needed for the database-scenario?.
     * @return static
     */
    public function scenarioTestDBs(bool $scenarioTestDBs): self
    {
        $this->scenarioTestDBs = $scenarioTestDBs;
        return $this;
    }

    /**
     * Set the snapshot settings.
     *
     * @param string|boolean $useSnapshotsWhenReusingDB    Take and import snapshots when reusing databases?
     *                                                     false, 'afterMigrations', 'afterSeeders', 'both'.
     * @param string|boolean $useSnapshotsWhenNotReusingDB Take and import snapshots when NOT reusing databases?
     *                                                     false, 'afterMigrations', 'afterSeeders', 'both'.
     * @return static
     */
    public function snapshots(
        $useSnapshotsWhenReusingDB,
        $useSnapshotsWhenNotReusingDB
    ): self {
        $this->useSnapshotsWhenReusingDB = $useSnapshotsWhenReusingDB;
        $this->useSnapshotsWhenNotReusingDB = $useSnapshotsWhenNotReusingDB;
        return $this;
    }

    /**
     * Turn the force-rebuild setting on (or off).
     *
     * @param boolean $forceRebuild Force the database to be rebuilt (or not).
     * @return static
     */
    public function forceRebuild(bool $forceRebuild = true): self
    {
        $this->forceRebuild = $forceRebuild;
        return $this;
    }

    /**
     * Set the mysql specific details.
     *
     * @param string $mysqlExecutablePath     The path to the "mysql" executable.
     * @param string $mysqldumpExecutablePath The path to the "mysqldump" executable.
     * @return static
     */
    public function mysqlSettings(
        string $mysqlExecutablePath,
        string $mysqldumpExecutablePath
    ): self {
        $this->mysqlExecutablePath = $mysqlExecutablePath;
        $this->mysqldumpExecutablePath = $mysqldumpExecutablePath;
        return $this;
    }

    /**
     * Set the postgres specific details.
     *
     * @param string $psqlExecutablePath   The path to the "psql" executable.
     * @param string $pgDumpExecutablePath The path to the "pg_dump" executable.
     * @return static
     */
    public function postgresSettings(
        string $psqlExecutablePath,
        string $pgDumpExecutablePath
    ): self {
        $this->psqlExecutablePath = $psqlExecutablePath;
        $this->pgDumpExecutablePath = $pgDumpExecutablePath;
        return $this;
    }



    /**
     * Set the number of seconds grace-period before stale databases and snapshots are to be deleted.
     *
     * @param integer $staleGraceSeconds The number of seconds.
     * @return static
     */
    public function staleGraceSeconds(int $staleGraceSeconds): self
    {
        $this->staleGraceSeconds = $staleGraceSeconds;
        return $this;
    }





    /**
     * Determine the seeders that need to be used.
     *
     * @return string[]
     */
    public function pickSeedersToInclude(): array
    {
        return $this->migrations ? $this->seeders : [];
    }

    /**
     * Pick the database dumps to import before the migrations run.
     *
     * @return string[]
     */
    public function pickPreMigrationImports(): array
    {
        $preMigrationImports = $this->preMigrationImports;
        $driver = $this->driver;

        $usePaths = [];
        if (isset($preMigrationImports[$driver])) {

            $paths = $preMigrationImports[$driver];
            $paths = is_string($paths) ? [$paths] : $paths;

            if (is_array($paths)) {
                foreach ($paths as $path) {
                    if (mb_strlen($path)) {
                        $usePaths[] = $path;
                    }
                }
            }
        }
        return $usePaths;
    }





    /**
     * Build a new ConfigDTO from the data given in a request to build the database remotely.
     *
     * @param string $payload The raw ConfigDTO data from the request.
     * @return self|null
     * @throws AdaptRemoteShareException When the version doesn't match.
     */
    public static function buildFromPayload(string $payload): ?self
    {
        if (!mb_strlen($payload)) {
            return null;
        }

        $values = json_decode($payload, true);
        if (!is_array($values)) {
            throw AdaptRemoteShareException::couldNotReadConfigDTO();
        }

        $configDTO = static::buildFromArray($values);

        if ($configDTO->dtoVersion != Settings::CONFIG_DTO_VERSION) {
            throw AdaptRemoteShareException::versionMismatch();
        }

        return $configDTO;
    }

    /**
     * Build the value to send in requests.
     *
     * @return string
     */
    public function buildPayload(): string
    {
        return json_encode(get_object_vars($this));
    }
}
