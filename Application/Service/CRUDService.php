<?php
/**
 * Created by PhpStorm.
 * User: banht_000
 * Date: 5/25/2015
 * Time: 1:54 PM
 */

namespace Application\Service;


class CRUDService {

    /**
     * @var Collection
     */
    protected $collection;


    /**
     * Get Item
     * @get(/count)
     *
     * @return array array of
     */
    public function count() {
        $query = $this->collection->buildQuery();
        return array ('total' => $query->count());
    }

    /**
     * Get Item
     * @get(/)
     * @param $page int optional #query
     * @param $count int optional #query
     *
     * @return array array of
     */
    public function getAll($page = null, $count = null) {
        $query = $this->collection->buildQuery();
        $query->paginate($page, $count);
        return $query->findAll();
    }


    /**
     * @get(/:id)
     * @param $id int required #path
     * @return Video
     */
    public function getItem($id) {
        return $this->collection->buildQuery()
            ->findBy('id', $id)
            ->first();
    }

    /**
     * @post(/)
     * @param $item Video #body
     * @return boolean isSuccess
     */
    public function save($item) {
        $this->collection->save($item);

        return $item;
    }


    /**
     * @delete(/:id)
     * @param $id int required #path
     *
     * @return int number of affected rows
     */
    public function delete($id) {
        return $this->collection->delete($id);
    }

}