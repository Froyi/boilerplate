<?php
declare(strict_types=1);

namespace Project\Module\Csv;

use Project\Module\DefaultService;

/**
 * Class CsvService
 * @package     Project\Module\Csv
 */
class CsvService extends DefaultService
{
    public function getCsvExample(): void
    {
        // output headers so that the file is downloaded rather than displayed
        header('Content-Encoding: UTF-8');
        header('Content-type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="file.csv"');

        // do not cache the file
        header('Pragma: no-cache');
        header('Expires: 0');
        echo "\xEF\xBB\xBF"; // UTF-8 BOM

        // create a file pointer connected to the output stream
        $file = fopen('php://output', 'wb');

        fputcsv($file, ['test1', 'test2', 'test3'], ';');
    }
}