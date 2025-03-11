<?php

namespace App\Repositories\Account;

use LaravelEasyRepository\Repository;

interface AccountRepository extends Repository{

    /**
     * get all Account data with pagination
     * @param $perPage
     * @return void
     */
    public function getAllPaginate($perPage);

    /**
     * get Account data by id
     * @param $id
     * @return void
     */
    public function getAccountId($id);
}
