<?php

namespace CodeDistortion\Adapt\Adapters\Interfaces;

use CodeDistortion\Adapt\DI\DIContainer;
use CodeDistortion\Adapt\DTO\ConfigDTO;
use CodeDistortion\Adapt\DTO\DatabaseMetaInfo;
use CodeDistortion\Adapt\Exceptions\AdaptBuildException;
use CodeDistortion\Adapt\Support\Hasher;

/**
 * Database-adapter methods related to managing reuse through transactions.
 */
interface ReuseTransactionInterface
{
    /**
     * Constructor.
     *
     * @param DIContainer $di        The dependency-injection container to use.
     * @param ConfigDTO   $configDTO A DTO containing the settings to use.
     * @param Hasher      $hasher    The object used to generate and check hashes.
     */
    public function __construct(DIContainer $di, ConfigDTO $configDTO, Hasher $hasher);


    /**
     * Insert details to the database to help identify if it can be reused or not.
     *
     * @param string  $origDBName          The name of the database that this test-database is for.
     * @param string  $buildHash           The current build-hash.
     * @param string  $snapshotHash        The current snapshot-hash.
     * @param string  $scenarioHash        The current scenario-hash.
     * @param boolean $transactionReusable Whether this database can be reused because of a transaction or not.
     * @param boolean $journalReusable     Whether this database can be reused because of journaling or not.
     * @param boolean $willVerify          Whether this database will be verified or not.
     * @return void
     */
    public function writeReuseMetaData(
        $origDBName,
        $buildHash,
        $snapshotHash,
        $scenarioHash,
        $transactionReusable,
        $journalReusable,
        $willVerify
    );

    /**
     * Remove the re-use meta-data table.
     *
     * @return void
     */
    public function removeReuseMetaTable();

    /**
     * Check to see if the database can be reused.
     *
     * @param string $buildHash    The current build-hash.
     * @param string $scenarioHash The current scenario-hash.
     * @param string $projectName  The project-name.
     * @param string $database     The database being built.
     * @return boolean
     * @throws AdaptBuildException When the database is owned by another project.
     */
    public function dbIsCleanForReuse(
        $buildHash,
        $scenarioHash,
        $projectName,
        $database
    ): bool;



    /**
     * Determine if a transaction can be used on this database (for database re-use).
     *
     * @return boolean
     */
    public function isTransactionable(): bool;

    /**
     * Start the transaction that the test will be encapsulated in.
     *
     * @return void
     */
    public function applyTransaction();

    /**
     * Check if the transaction was committed.
     *
     * @return boolean
     */
    public function wasTransactionCommitted(): bool;
}
