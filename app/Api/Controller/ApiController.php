<?php namespace App\Api\Controller;


/**
 * Base ApiController for All Other ApiController 
 */
use Laravel\Lumen\Routing\Controller as BaseController;
use Illuminate\Http\Response as IlluminateResponse;

class ApiController extends BaseController
{
    protected $statusCode = 200;

    /**
     * get method for statuscode
     * @return [int] [description]
     */
    public function getStatusCode()
    {
    	return $this->statusCode;
    }

    /**
     * set method for statuscode
     * @param $statusCode
     */
    public function setStatusCode($statusCode)
    {
    	$this->statusCode = $statusCode;

    	return $this;
    }


    /**
     * response not found 
     * @param  string $message
     * @return json
     */
    public function responseNotFound($message)
    {
    	return $this->setStatusCode(IlluminateResponse::HTTP_NOT_FOUND)->responseWithError($message);
    }


    /**
     * response with error
     * @param  string $message
     * @return json
     */
    public function responseWithError($message)
    {
    	return $this->response([

    		'error' => [
    			'message' => $message,
    			'code' => $this->getStatusCode()
    			]

    		]);
    }


    /**
     * response with pagination [for just testing]
     * @param  model $model
     * @param  array model  $data
     * @return json
     */
    public function responseWithPagination($model, $data)
    {
        $data = array_merge($data, [
                'paginate' => [
                        'total_count'  => $model->total(),
                        'total_pages'  => ceil($model->total() / $model->perPage()),
                        'current_page' => $model->currentPage(),
                        'limit'        => $model->perPage()
                ]
            ]);
        return $this->response($data);
    }


    /**
     * response
     * @param  array $data
     * @param  array  $headers
     * @return json
     */
    public function response($data, $headers = [])
    {
    	return response()->json($data,$this->getStatusCode(),$headers);
    }
}
