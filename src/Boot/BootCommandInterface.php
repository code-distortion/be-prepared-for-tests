<?php

namespace CodeDistortion\Adapt\Boot;

use CodeDistortion\Adapt\DatabaseBuilder;
use CodeDistortion\Adapt\Exceptions\AdaptConfigException;

/**
 * Bootstrap Adapt for commands.
 */
interface BootCommandInterface
{
    /**
     * Ensure the storage-directories exist.
     *
     * @return static
     * @throws AdaptConfigException When the storage directory cannot be created.
     */
    public function ensureStorageDirsExist(): self;

    /**
     * Create a new DatabaseBuilder object and set its initial values.
     *
     * @param string $connection The database connection to prepare.
     * @return DatabaseBuilder
     */
    public function makeNewBuilder(string $connection): DatabaseBuilder;

    /**
     * Work out if stale things are allowed to be purged.
     *
     * @return boolean
     */
    public function canPurgeStaleThings(): bool;
}
