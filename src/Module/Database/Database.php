<?php
declare (strict_types=1);

namespace Project\Module\Database;

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

    /** @var  array $query */
    protected $query;

    /** @var \PDO $connection */
    protected $connection;

    /**
     * Database constructor.
     * @param Configuration $configuration
     * @throws \InvalidArgumentException
     */
    public function __construct(Configuration $configuration)
    {
        $databaseConfiguration = $configuration->getEntryByName('database');

        $this->host = $databaseConfiguration['host'];
        $this->user = $databaseConfiguration['user'];
        $this->password = $databaseConfiguration['password'];
        $this->database = $databaseConfiguration['database'];

        $this->connect();
    }

    /**
     * connect to database
     */
    public function connect(): void
    {
        $dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->database;
        $this->connection = new \PDO($dsn, $this->user, $this->password);
    }

    /**
     * @param string $table
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
     * @return Query
     */
    public function getNewDeleteQuery(string $table): Query
    {
        $query = new Query($table);
        $query->addType(Query::DELETE);

        return $query;
    }


    /**
     * @param Query $query
     * @return array
     */
    public function fetchAll(Query $query): array
    {
        $sql = $this->connection->query($query->getQuery());

        return $sql->fetchAll(\PDO::FETCH_OBJ);
    }

    /**
     * @param Query $query
     * @return mixed
     */
    public function fetch(Query $query)
    {
        $sql = $this->connection->query($query->getQuery());

        return $sql->fetch(\PDO::FETCH_OBJ);
    }

    /**
     * @param Query $query
     * @return bool
     */
    public function execute(Query $query): bool
    {
        $sql = $this->connection->prepare($query->getQuery());

        return $sql->execute();
    }
}