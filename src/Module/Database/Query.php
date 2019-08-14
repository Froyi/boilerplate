<?php declare (strict_types=1);

namespace Project\Module\Database;

use function is_bool;
use function is_string;
use UnexpectedValueException;

/**
 * Class Query
 *
 * TYPE | TABLE | WHERE | ORDER | LIMIT
 *
 * @package Project\Module\Database
 */
class Query
{
    public const SELECT = 'SELECT ';
    public const UPDATE = 'UPDATE ';
    public const INSERT = 'INSERT INTO' . ' ';
    public const DELETE = 'DELETE ';
    public const TRUNCATE = 'TRUNCATE ';
    public const FROM = 'FROM ';
    public const WHERE = 'WHERE ';
    public const AND = 'AND ';
    public const OR = 'OR ';
    public const LIMIT = 'LIMIT ';
    public const ORDERBY = 'ORDER BY ';
    public const ORDER_RAND = 'RAND() ';
    public const ASC = 'ASC';
    public const DESC = 'DESC';
    public const SET = 'SET ';
    public const VALUES = 'VALUES';

    /** @var array $tableArray */
    protected $tableArray = [];

    /** @var  string $type */
    protected $type;

    /** @var array $entityArray */
    protected $entityArray = [];

    /** @var  string $where */
    protected $where;

    /** @var array $andOr */
    protected $andOr = [];

    /** @var  string $orderBy */
    protected $orderBy;

    /** @var  string $limit */
    protected $limit;

    /** @var  string $set */
    protected $set;

    /** @var array $insert */
    protected $insert = [];

    /** @var null|string */
    protected $query;

    /**
     * Query constructor.
     *
     * @param string $table
     */
    public function __construct(string $table)
    {
        $this->addTable($table);
    }

    /**
     * @param string $type
     */
    public function addType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $entity
     */
    public function addEntityToType(string $entity): void
    {
        $this->entityArray[] = $entity;
    }

    /**
     * @param string $table
     */
    public function addTable(string $table): void
    {
        $this->tableArray[] = $table;
    }

    /**
     * @param string $entity
     * @param string $operator
     * @param        $value
     */
    public function where(string $entity, string $operator, $value): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }

        if ($value === null) {
            $operator = 'is';
            $value = 'NULL';
        } else if (is_string($value) === true && $value !== 'NULL') {
            $value = '\'' . addslashes($value) . '\'';
        }

        $this->where .= self::WHERE . $entity . ' ' . $operator . ' ' . $value . ' ';
    }

    /**
     * @param string $entity
     * @param string $operator
     * @param        $value
     */
    public function andWhere(string $entity, string $operator, $value): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }

        if (is_string($value) === true && $value !== 'NULL') {
            $value = '\'' . $value . '\'';
        }

        $this->where .= self:: AND . $entity . ' ' . $operator . ' ' . $value . ' ';
    }

    /**
     * @param string $entity
     * @param string $operator
     * @param        $value
     */
    public function orWhere(string $entity, string $operator, $value): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }

        if (is_string($value) === true) {
            $value = '\'' . $value . '\'';
        }

        $this->where .= self:: OR . $entity . ' ' . $operator . ' ' . $value . ' ';
    }

    /**
     * @param string $entity
     * @param string $operator
     * @param        $value
     * @param bool $asParam
     */
    public function andOrWhere(string $entity, string $operator, $value, bool $asParam = false): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }

        if (is_string($value) === true && $asParam === false) {
            $value = '\'' . $value . '\'';
        }

        $this->andOr[] = $entity . ' ' . $operator . ' ' . $value . ' ';
    }

    /**
     * @param string $entity
     * @param null $value
     */
    public function set(string $entity, $value = null): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }

        if ($value !== null && is_string($value) === true) {
            $value = '\'' . addslashes($value) . '\'';
        }

        if (!empty($this->set)) {
            $this->set .= ', ';
        }

        if ($value === null) {
            $this->set .= $entity . ' = null ';
        } else {
            $this->set .= $entity . ' = ' . $value . ' ';
        }
    }

    /**
     * @param string $entity
     * @param null $value
     */
    public function insert(string $entity, $value = null): void
    {
        if (is_bool($value) === true){
            $value = (int)$value;
        }
        if (!isset($this->insert[$entity])) {
            $this->insert[$entity] = $value;
        }
    }

    /**
     * @param int $limit
     */
    public function limit(int $limit): void
    {
        $this->limit = self::LIMIT . $limit;
    }

    /**
     * @param string $entity
     * @param string $order
     */
    public function orderBy(string $entity, string $order): void
    {
        $this->orderBy = self::ORDERBY . ' ' . $entity . ' ' . $order . ' ';
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        $queryString = '';

        try {
            switch ($this->type) {
                case self::SELECT:
                    $queryString .= self::SELECT . $this->getEntities();
                    $queryString .= self::FROM . $this->getTables();
                    $queryString .= $this->where;
                    $queryString .= $this->getAndOr();

                    $queryString .= $this->orderBy;
                    $queryString .= $this->limit;
                    break;
                case self::UPDATE:
                    $queryString .= self::UPDATE . $this->getTables();
                    $queryString .= self::SET . $this->set;
                    $queryString .= $this->where;
                    break;
                case self::INSERT:
                    $queryString .= self::INSERT . $this->getTables();
                    $queryString .= $this->getInserts();
                    break;
                case self::DELETE:
                    $queryString .= self::DELETE . self::FROM . $this->getTables();
                    $queryString .= $this->where;
                    break;
                case self::TRUNCATE:
                    $queryString .= self::TRUNCATE . $this->getTables();
                    break;
                default:
                    break;
            }

            return $queryString;
        } catch (UnexpectedValueException $exception) {
            // maybe logging

            return '';
        }
    }

    /**
     * @param string $query
     *
     * @return Query
     */
    public function setQuery(string $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function orderRandom(): void
    {
        $this->orderBy = self::ORDERBY . self::ORDER_RAND;
    }

    /**
     * @return string
     */
    protected function getEntities(): string
    {
        $entities = '* ';

        if (empty($this->entityArray)) {
            return $entities;
        }

        return implode(',', $this->entityArray) . ' ';
    }

    /**
     * @return string
     */
    protected function getAndOr(): string
    {
        if (empty($this->andOr)) {
            return '';
        }

        return 'AND (' . implode(self:: OR, $this->andOr) . ')';
    }

    /**
     * @return string
     * @throws UnexpectedValueException
     */
    protected function getTables(): string
    {
        if (empty($this->tableArray)) {
            throw new UnexpectedValueException('Es wurde keine Tabelle angegeben!');
        }

        return implode(',', $this->tableArray) . ' ';
    }

    /**
     * @return string
     */
    protected function getInserts(): string
    {
        $entities = '';
        $values = '';
        foreach ($this->insert as $entity => $value) {
            if (!empty($entities)) {
                $entities .= ', ';
                $values .= ', ';
            }

            $entities .= $entity;

            if (is_string($value) === true) {
                $value = '\'' . addslashes($value) . '\'';
            }

            if ($value === null) {
                $value = 'null';
            }
            $values .= $value;
        }

        return '(' . $entities . ') ' . self::VALUES . '(' . $values . ')';
    }
}