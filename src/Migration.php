<?php
declare(strict_types=1);

namespace Project;

use Project\Module\Database\Database;

/**
 * Class Migration
 * @package     Project
 */
class Migration
{
    /** @var string TABLE */
    protected const TABLE = 'migrations';

    protected const MIGRATION_FILE = ROOT_PATH . '/migrations.php';

    /** @var array $migrations */
    protected $migrations;

    /** @var Database $database */
    protected $database;

    /** @var string $output */
    protected $output;

    /**
     * Migration constructor.
     *
     * @param Database $database
     */
    public function __construct(Database $database)
    {
        $this->database = $database;
        /** @noinspection PhpIncludeInspection */
        $migrations = include self::MIGRATION_FILE;
        ksort($migrations);
        $this->migrations = $migrations;

        $this->output = '';
    }

    /**
     * @return bool
     */
    public function migrate(): bool
    {
        foreach ($this->migrations as $dateString => $migrationArray) {
            foreach ($migrationArray as $migrationVersion => $migration) {
                $version = $this->getMigrationVersion($dateString, $migrationVersion);

                if ($this->isMigrationExecuted($version) === false) {
                    if ($this->migrateVersion($migration, $version) === true) {
                        $this->output = '<p>+++ Migration ' . $version . ' wurde erfolgreich eingetragen.</p>' . $this->output;
                    } else {
                        $this->output = '<p>!!! Migration ' . $version . ' wurde nicht eingetragen.<br/>' . $migration . '</p>';
                        return false;
                    }
                } else {
                    $this->output = '<p>--- Migration ' . $version . ' wurde bereits eingetragen.</p>';
                }
            }
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDatabaseUpToDate(): bool
    {
        $allMigrationVersions = $this->getAllMigrationVersions();

        if (empty($allMigrationVersions) === true && empty($this->migrations) === true) {
            return true;
        }

        foreach ($this->migrations as $dateString => $migrationArray) {
            foreach ($migrationArray as $migrationVersion => $migration) {
                $version = $this->getMigrationVersion($dateString, $migrationVersion);

                if ($this->isMigrationExecuted($version) === false) {
                    return false;
                }
            }
        }

        foreach ($allMigrationVersions as $migration) {
            $versionArray = explode('|', $migration->migrationVersion);
            if (isset($this->migrations[$versionArray[0]][$versionArray[1]]) === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getOutput(): string
    {
        return $this->output;
    }

    /**
     * @param string $migrationVersion
     *
     * @return bool
     */
    protected function isMigrationExecuted(string $migrationVersion): bool
    {
        $query = $this->database->getNewSelectQuery(self::TABLE);

        $query->where('migrationVersion ', '=', $migrationVersion);

        $result = $this->database->fetch($query);

        return empty($result) === false;
    }

    /**
     * @param string $dateString
     * @param int $migrationVersion
     *
     * @return string
     */
    protected function getMigrationVersion(string $dateString, int $migrationVersion): string
    {
        return $dateString . '|' . $migrationVersion;
    }

    /**
     * @param string $migration
     * @param string $version
     *
     * @return bool
     */
    protected function migrateVersion(string $migration, string $version): bool
    {
        if ($this->database->executeQueryString($migration) === true) {
            return $this->addMigrationVersion($version);
        }

        return false;
    }

    protected function addMigrationVersion(string $version): bool
    {
        $query = $this->database->getNewInsertQuery(self::TABLE);
        $query->insert('migrationVersion', $version);

        return $this->database->execute($query);
    }

    /**
     * @return array
     */
    protected function getAllMigrationVersions(): array
    {
        $query = $this->database->getNewSelectQuery(self::TABLE);

        return $this->database->fetchAll($query);
    }

}