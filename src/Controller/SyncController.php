<?php
declare(strict_types=1);

namespace Project\Controller;

use Project\Migration;
use Project\Utilities\Tools;

/**
 * Class MergeController
 * @package     Project\Controller
 */
class SyncController extends DefaultController
{
    public function migrateAction(): void
    {
        $routeName = Tools::getRefererRoute('index');

        $migrateService = new Migration($this->database);
        if ($migrateService->migrate() === false) {
            $this->errorRouting($routeName, $migrateService->getOutput());
            exit;
        }

        $this->successRouting($routeName, $migrateService->getOutput());
    }

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