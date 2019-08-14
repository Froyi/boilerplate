<?php
declare(strict_types=1);

namespace Project\Controller;

/**
 * Class MergeController
 * @package     Project\Controller
 */
class SyncController extends DefaultController
{
    /**
     * @return bool
     */
    private function clearAllTables(): bool
    {
        $tablesToClear = ['table1', 'table2'];

        foreach ($tablesToClear as $table) {
            $query = $this->database->getNewTruncatQuery($table);
            if ($this->database->execute($query) === false) {
                return false;
            }
        }

        return true;
    }

}