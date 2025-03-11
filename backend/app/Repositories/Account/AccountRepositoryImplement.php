<?php

namespace App\Repositories\Account;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Account;

class AccountRepositoryImplement extends Eloquent implements AccountRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(Account $model)
    {
        $this->model = $model;
    }

    /**
     * get all Account data with pagination
     * @param $perPage
     * @return mixed
     */
    public function getAllPaginate($perPage)
    {
        return $this->model->paginate($perPage);
    }

    /**
     * get Account data by id
     * @param $id
     * @return mixed
     */
    public function getAccountId($id)
    {
        return $this->model->find($id);
    }
}
