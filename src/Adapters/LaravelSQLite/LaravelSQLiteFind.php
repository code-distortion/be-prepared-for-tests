<?php

namespace CodeDistortion\Adapt\Adapters\LaravelSQLite;

use CodeDistortion\Adapt\Adapters\AbstractClasses\AbstractFind;
use CodeDistortion\Adapt\Adapters\Interfaces\FindInterface;
use CodeDistortion\Adapt\DTO\DatabaseMetaInfo;
use CodeDistortion\Adapt\Exceptions\AdaptBuildException;
use CodeDistortion\Adapt\Support\Settings;
use Throwable;

/**
 * Database-adapter methods related to finding Laravel/SQLite databases.
 */
class LaravelSQLiteFind extends AbstractFind implements FindInterface
{
    /**
     * Generate the list of existing databases.
     *
     * @return string[]
     */
    protected function listDatabases(): array
    {
        return $this->di->filesystem->dirExists($this->configDTO->storageDir)
            ? $this->di->filesystem->filesInDir($this->configDTO->storageDir)
            : [];
    }

    /**
     * Check if this database should be ignored.
     *
     * @param string $database The database to check.
     * @return boolean
     */
    protected function shouldIgnoreDatabase(string $database): bool
    {
        // ignore other files
        $temp = preg_split('/[\\\\\/]+/', $database);
        $filename = array_pop($temp);
        return in_array($filename, ['.gitignore', 'purge-lock']);
    }

    /**
     * Build DatabaseMetaInfo objects for a database.
     *
     * @param string $database  The database to use.
     * @param string $buildHash The current build-hash.
     * @return DatabaseMetaInfo|null
     */
    protected function buildDatabaseMetaInfo(string $database, string $buildHash): ?DatabaseMetaInfo
    {
        $pdo = $this->di->db->newPDO($database);
        return $this->buildDatabaseMetaInfoX(
            $this->di->db->getConnection(),
            $database,
            $pdo->fetchReuseTableInfo("SELECT * FROM `" . Settings::REUSE_TABLE . "` LIMIT 0, 1"),
            $buildHash
        );
    }

    /**
     * Remove the given database.
     *
     * @param DatabaseMetaInfo $databaseMetaInfo The info object representing the database.
     * @return boolean
     * @throws AdaptBuildException When the database cannot be removed.
     */
    protected function removeDatabase(DatabaseMetaInfo $databaseMetaInfo): bool
    {
        if (!$this->di->filesystem->fileExists($databaseMetaInfo->name)) {
            return false;
        }

        try {
            if (!$this->di->filesystem->unlink($databaseMetaInfo->name)) {
                throw AdaptBuildException::couldNotDropDatabase($databaseMetaInfo->name);
            }
            return true;
        } catch (Throwable $e) {
            throw $e instanceof AdaptBuildException
                ? $e
                : AdaptBuildException::couldNotDropDatabase($databaseMetaInfo->name, $e);
        }
    }

    /**
     * Get the database's size in bytes.
     *
     * @param string $database The database to get the size of.
     * @return integer|null
     */
    protected function size(string $database): ?int
    {
        return $this->di->filesystem->size($database);
    }
}
