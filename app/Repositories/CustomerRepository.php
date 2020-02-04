<?php

namespace App\Repositories;

use App\Customer as Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */
class CustomerRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получить список статей для вывода в списке
     * (Админка)
     *
     * @return LengthAwarePaginator
     */
    public function getAllWithPaginate()
    {
        $columns = [
            'id',
            'name',
            'email',
            'phone',
            'website',
            'user_id',
        ];

        $result = $this->startConditions()
//            ->select($columns)
//            ->orderBy('name', 'DESC')
            ->paginate(2);

        return $result;
    }

    /**
     * Получить модель для редактирования в админке
     *
     * @param $id
     *
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }
}
