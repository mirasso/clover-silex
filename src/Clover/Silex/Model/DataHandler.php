<?php
namespace Clover\Silex\Model;

use Doctrine\DBAL\Connection;

abstract class DataHandler
{
    abstract public function getTableName();

    /**
     * @var
     */
    protected $conn;

    /**
     * @param Connection $conn
     */
    public function __construct(Connection $conn)
    {
        $this->conn = $conn;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->conn->createQueryBuilder()
            ->select('*')
            ->from($this->getTableName(), 't')
            ->execute()
            ->fetchAll();
    }

    /**
     * Inserts a table row with specified data.
     *
     * @param array $data An associative array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function insert(array $data)
    {
        if (!array_key_exists('created_at', $data)) {
            $now = new \DateTime('now');
            $data['created_at'] = $now->format('Y-m-d H:i:s');
        }
        if (array_key_exists('updated_at', $data)) {
            unset($data['updated_at']);
        }
        return $this->conn->insert($this->getTableName(), $data);
    }

    /**
     * Executes an SQL UPDATE statement on a table.
     *
     * @param array $data An associative array containing column-value pairs.
     * @param array $identifier The update criteria. An associative array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function update(array $data, array $identifier)
    {
        if (array_key_exists('created_at', $data)) {
            unset($data['created_at']);
        }
        if (!array_key_exists('updated_at', $data)) {
            $now = new \DateTime('now');
            $data['updated_at'] = $now->format('Y-m-d H:i:s');
        }
        return $this->conn->update($this->getTableName(), $data, $identifier);
    }

    /**
     * Executes an SQL DELETE statement on a table.
     *
     * @param array $identifier The deletion criteria. An associative array containing column-value pairs.
     * @return integer The number of affected rows.
     */
    public function delete(array $identifier)
    {
        return $this->conn->delete($this->getTableName(), $identifier);
    }

    /**
     * Returns the ID of the last inserted row, or the last value from a sequence object.
     *
     * @param string $seqName Name of the sequence object from which the ID should be returned.
     * @return string A string representation of the last inserted ID.
     */
    public function lastInsertId($seqName = null)
    {
        return $this->conn->lastInsertId($seqName);
    }

    /**
     * Returns a record by supplied id.
     *
     * @param mixed $id
     * @return array
     */
    public function find($id)
    {
        return $this->conn->fetchAssoc(
            sprintf('select * from %s where id=?', $this->getTableName()),
            [(int) $id]
        );
    }
}
