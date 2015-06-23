<?php namespace App\Model;

use Hexcores\MongoLite\Query;

/**
 * This file is part of elecapi project
 * Abstract class for application model
 *
 * @author  Hexcores Web and Mobile Studio <support@hexcores.com>
 * @package App\Model
 */
abstract class AbstractModel
{
    /**
     * Mongo lite query instance
     * @var Hexcores\MongoLite\Query
     */
    protected $collection;

    /**
     * Mongodb connection
     * @var Hexcores\MongoLite\Connection
     */
    protected $connection;

    /**
     * Constructor method for abstract model class
     */
    public function __construct()
    {
        $this->connection = app('connection');

        $this->collection = new Query($this->connection->collection($this->getCollectionName()));
    }

    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Get all documents from specific collection
     * @return array
     */
    public function all()
    {
        return $this->getCollection()->all();
    }

    /**
     * Get many document form specific collection
     * @param  integer $limit  
     * @param  integer $offset Offset or skip
     * @return array
     */
    public function getMany($limit = 10, $offset = 0)
    {
        return $this->getCollection()->all($limit, $offset);
    }

    /**
     * Get many document with where statement
     * @param  string $key   Document key
     * @param  mix    $value
     * @param  array  $field
     * @return array
     */
    public function getManyBy($key, $value, array $field = [])
    {
        return $this->getCollection()->where($key, '=', $value)->get($field);
    }

    /**
     * Get one document by specific key value pair
     * @param  string $key   Mongo document key
     * @param  mix    $value
     * @return Hexcores\MongoLite\Document|null
     */
    public function getBy($key, $value)
    {
        return $this->getCollection()->first([$key, $value]);
    }

    /**
     * Get one document by mongo id
     * @param  string $id
     * @return Hexcores\MongoLite\Document|null
     */
    public function get($id)
    {
        return $this->getCollection()->first($id);
    }


    public function count()
    {
        return $this->getCollection()->count();
    }

    /**
     * Return mongo collection name to be connected
     *
     * <code>
     *     public function getCollectionName()
     *     {
     *         return 'user';
     *     }
     * </code>
     * 
     * @return string
     */
    abstract public function getCollectionName();

    /**
     * Insert newly data to specific mongo collection
     * @param  array  $data
     * @return mix
     */
    abstract public function create(array $data);
}
