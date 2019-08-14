<?php
declare (strict_types=1);

namespace Project\Module\Database;

use InvalidArgumentException;
use PDO;
use PDOException;
use Project\Configuration;

/**
 * Class Database
 * @package Project\Module\Database
 */
class Database
{
    /** @var  string $host */
    protected $host;

    /** @var  string $user */
    protected $user;

    /** @var  string $password */
    protected $password;

    /** @var  string $database */
    protected $database;

    /** @var PDO $connection */
    protected $connection;

    /**
     * Database constructor.
     *
     * @param Configuration $configuration
     *
     * @throws InvalidArgumentException
     */
    public function __construct(Configuration $configuration)
    {
        $databaseConfiguration = $configuration->getEntryByName('database');

        $this->host = $databaseConfiguration['host'];
        $this->user = $databaseConfiguration['user'];
        $this->password = $databaseConfiguration['password'];
        $this->database = $databaseConfiguration['database_name'];

        $this->connect();
    }

    /**
     * connect to database
     */
    public function connect(): void
    {
        $dsn = 'mysql:host=' . $this->host . ';port=3308;dbname=' . $this->database . ';charset=utf8';
        $this->connection = new PDO($dsn, $this->user, $this->password);
    }

    public function __destruct()
    {
        $this->connection = null;
    }

    /**
     * @param string $table
     *
     * @return Query
     */
    public function getNewSelectQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::SELECT);

        return $query;
    }

    /**
     * @param string $table
     *
     * @return Query
     */
    public function getNewUpdateQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::UPDATE);

        return $query;
    }

    /**
     * @param string $table
     *
     * @return Query
     */
    public function getNewInsertQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::INSERT);

        return $query;
    }

    /**
     * @param string $table
     *
     * @return Query
     */
    public function getNewDeleteQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::DELETE);

        return $query;
    }

    /**
     * @param string $table
     *
     * @return Query
     */
    public function getNewTruncatQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::TRUNCATE);

        return $query;
    }

    /**
     * @param Query $query
     *
     * @return array
     */
    public function fetchAll(Query $query): array
    {
        $sql = $this->connection->query($query->getQuery());

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $query
     *
     * @return array
     */
    public function fetchAllQueryString(string $query): array
    {
        $sql = $this->connection->query($query);

        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param Query $query
     *
     * @return mixed
     */
    public function fetch(Query $query)
    {
        $sql = $this->connection->query($query->getQuery());

        return $sql->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param Query $query
     *
     * @return bool
     */
    public function execute(Query $query): bool
    {
        $sql = $this->connection->prepare($query->getQuery());

        return $sql->execute();
    }

    /**
     * @return bool
     */
    public function beginTransaction(): bool
    {
        try {
            return $this->connection->beginTransaction();
        } catch (PDOException $exception) {
            // maybe logging

            return false;
        }
    }

    /**
     * @return bool
     */
    public function commit(): bool
    {
        return $this->connection->commit();
    }

    /**
     * @return bool
     */
    public function rollBack(): bool
    {
        return $this->connection->rollBack();
    }

    /**
     * @param string $table
     *
     * @return int
     */
    public function count(string $table): int
    {
        $query = new Query($table);
        $query->setQuery('SELECT count(*) as amount FROM ' . $table);

        $result = $this->fetch($query);

        if (empty($result) === true) {
            return 0;
        }

        return (int)$result->amount;
    }

    /**
     * @param string $table
     *
     * @return bool
     */
    public function truncateTable(string $table): bool
    {
        $query = new Query($table);
        $query->addType(Query::TRUNCATE);

        return $this->execute($query);
    }

    /**
     * @param string $queryString
     *
     * @return bool
     */
    public function executeQueryString(string $queryString): bool
    {
        $sql = $this->connection->prepare($queryString);

        return $sql->execute();
    }
}