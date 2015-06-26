<?php namespace App\Model;

use MongoRegex;

use Hexcores\MongoLite\Query;

use Illuminate\Pagination\LengthAwarePaginator;

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

    /**
     * Get count of all documents from specific collection
     * @return int
     */
    public function count()
    {
        return $this->getCollection()->count();
    }

    /**
     * Get documents by like 
     * @param  string $key   
     * @param  string $value 
     * @param  string $opt   regex option
     * @return Hexcores\MongoLite\Query
     */
    public function like($key, $value, $opt = 'im')
    {
        $value = new MongoRegex('/'.$value.'/'.$opt);

        return $this->getCollection()->where($key, '=', $value);
    }

    /**
     * Get documents with pagination
     * @param  integer $limit    [limit of query ]
     * @param  integer  $page     [page view]
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($limit = 10 , $page = 1)
    {
        $skip = $page != 1 ? $limit * ($page - 1 ): 0;

        return $this->changePaginater($limit, $this->getMany($limit,$skip), $this->count(), $page);
    }
    
    /**
     * Change to Pagination
     * @param  integer $limit   [limit of query]
     * @param  mixed  $results
     * @param  int  $total   [count of all documents]
     * @param  int  $page    [page view]
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    protected function changePaginater($limit = 10, $results, $total, $page)
    {
        return new LengthAwarePaginator($results, $total, $limit, $page);
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
