<?php namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * To change pagination
     * @param  mixed $model
     * @param  array  $data
     * @return array
     */
    public function withPagination($model, array $data)
    {
        $data = array_merge($data, [
                'paginate' => [
                        'total_count'  => $model->total(),
                        'total_pages'  => $model->lastPage(),
                        'current_page' => $model->currentPage(),
                        'limit'        => $model->perPage()
                ]
            ]);
        return $data;
    }
}
