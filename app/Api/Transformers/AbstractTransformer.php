<?php namespace App\Api\Transformers;


/**
 * Abstract  Transformer for other transformer class.
 * Transform field of database to json data
 */
abstract class AbstractTransformer {


	/**
	 * Transform a collection
	 * @param array $items 
	 * @return  array
	 */
	public function transformCollection(array $items)
	{
		return array_map([$this, 'transform'], $items);
	}

	/**
	 * transform method
	 * @param  array $items
	 * @return array
	 */
	public abstract function transform($items);

}