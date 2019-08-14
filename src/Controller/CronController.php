<?php
declare(strict_types=1);

namespace Project\Controller;

use Project\Module\GenericValueObject\Datetime;
use Project\Module\GenericValueObject\Id;

/**
 * Class CronController
 * @package     Project\Controller
 */
class CronController extends DefaultController
{
    public function testCronAction(): void
    {
        $query = $this->database->getNewInsertQuery('errorlog');
        $query->insert('errorLogId', Id::generateId()->toString());
        $query->insert('created', Datetime::fromValue('now')->toString());
        $query->insert('message', 'CRON');
        $query->insert('level', 'Info');

        $this->database->execute($query);
    }
}