<?php


namespace App\Repositories\Base;

use App\Repositories\Base\BaseInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements BaseInterface
{
    /**
     * @var Model
     */
    protected $model;

    protected $search = [];

    public function __construct()
    {
        $this->makeModel();
    }

    abstract public function model();

    public function resetModel()
    {
        $this->makeModel();
    }

    public function makeModel()
    {
        $model = app($this->model());
        return $this->model = $model;
    }

    public function find($id)
    {
        $query = $this->model;
        $this->resetModel();
        return $query->find($id);
    }

    public function first(array $where, array $orderBy = [])
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        if (!empty($orderBy)) {
            $this->orderBy($query, $orderBy);
        }
        $this->resetModel();
        return $query->first();
    }

    public function firstOrCreate(array $where)
    {
        $query = $this->model;
        $this->resetModel();
        return $query->firstOrCreate($where);
    }

    public function get(array $where = [], array $orderBy = [], array $groupBy = [], $select = [], $limit = 0)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        if (!empty($select)) {
            $query = $query->select($select);
        }
        if ($groupBy) {
            $query = $query->groupBy($groupBy);
        }
        if (!empty($orderBy)) {
            $this->orderBy($query, $orderBy);
        }
        if(!empty($limit)){
            $query = $query->take($limit);
        }
        $this->resetModel();
        return $query->get();
    }

    public function count(array $where)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->count();
    }

    public function countDistinct(array $where, $distinct_field)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $query = $query->distinct($distinct_field);
        $this->resetModel();
        return $query->count();
    }

    public function sum(array $where, $field)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->sum($field);
    }

    public function push(array $where, $field, $value, $unique = true)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->push($field, $value, $unique);
    }

    public function pull(array $where, $field, $value)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->pull($field, $value);
    }

    public function all()
    {
        $query = $this->model;
        $this->resetModel();
        return $query->all();
    }

    public function pluck($column, $key = null)
    {
        $query = $this->model;
        $this->resetModel();
        return $query->pluck($column, $key);
    }

    public function pluckWhere(array $where, $column, $key = null)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->pluck($column, $key);
    }

    public function increment(array $where, $column, $value = 1)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->increment($column, $value);
    }

    public function decrement(array $where, $column, $value = 1)
    {
        $query = $this->model;
        if ($where) {
            $this->applyConditions($query, $where);
        }
        $this->resetModel();
        return $query->decrement($column, $value);
    }

    /**
     * @param $data
     * @return bool|mixed
     */
    public function create($data)
    {
        $query = $this->model->newInstance($data);
        $query->save();
        $this->resetModel();
        return $query;
    }

    /**
     * @param $data
     */
    public function createMany($data)
    {
        $this->model->insert($data);
        $this->resetModel();
    }

    public function findOrFail($id)
    {
        return $this->model->findOrFail($id);
    }

    public function edit($model, $data)
    {
        $query = $model->fill($data);
        $query->save();
        return $query;
    }

    public function editWhere(array $where, array $data, $limit = 0, $offset = 0)
    {
        $query = $this->model;

        if (!empty($where)) {
            $this->applyConditions($query, $where);
        }
        if ($limit > 0) {
            $query = $query->offset($offset)->limit($limit);
        }
        $query->update($data);
        $this->resetModel();
        return $query;
    }

    public function delete($model)
    {
        $model->delete();
        return true;
    }

    public function upsert($data, $unique_column, $update_column)
    {
        $this->model->upsert($data, $unique_column, $update_column);
        $this->resetModel();
        return true;
    }

    public function updateOrCreate($data, $data_update)
    {
        $this->model->updateOrCreate($data, $data_update);
        $this->resetModel();
        return true;
    }

    public function deleteWhere(array $where = [])
    {
        $query = $this->model;

        if (!empty($where)) {
            $this->applyConditions($query, $where);
        }

        $query->delete();

        return true;
    }

    public function paginate(array $where = [], array $orderBy = [], array $select = [], array $with = [], $limit = 10)
    {
        $query = $this->model;
        if (!empty($select)) {
            if (is_array($select)) {
                $query = $query->select(implode(',', $select));
            } else {
                $query = $query->select($select);
            }

        }
        if (!empty($where)) {
            $this->applyConditions($query, $where);
        }
        if (!empty($orderBy)) {
            $this->orderBy($query, $orderBy);
        }
        if (!empty($with)) {
            $query->with($with);
        }
        $query = $query->paginate($limit);
        $this->resetModel();
        return $query;
    }

    /**
     * @param $query
     * @param array $where
     * @return mixed
     */
    protected function applyConditions(&$query, $where = [])
    {
        foreach ($where as $field => $value) {
            switch ($field) {
                case 'orWhere':
                    $query = $query->where(function ($query) use ($value) {
                        foreach ($value as $f => $v) {
                            $this->where($query, $f, $v, 'orWhere');
                        }
                    });
                    break;
                default:
                    $this->where($query, $field, $value);
            }
        }
        //return $query;
    }

    public function whereOne($column, $value)
    {
        return $this->model->where($column, $value)->get();
    }


    public function where(&$query, $field, $value, $method = 'where')
    {
        if (is_array($value)) {
            list($field, $condition, $val) = $value;
            switch ($condition) {
                case 'whereIn':
                    $query = $query->whereIn($field, $val);
                    break;
                case 'whereNotIn':
                    $query = $query->whereNotIn($field, $val);
                    break;
                case 'whereBetween':
                    $query = $query->whereBetween($field, $val);
                    break;
                case 'like':
                    $query = $query->$method($field, $condition, '%' . $val . '%');
                    break;
                default:
                    $query = $query->$method($field, $condition, $val);
            }
        } else {
            $query = $query->$method($field, $value);
        }
    }

    public function orderBy(&$query, $orderBy)
    {
        foreach ($orderBy as $column => $direction) {
            $query = $query->orderBy($column, $direction);
        }
    }

    public function aggregate(array $pipeline)
    {
        $result = $this->model->raw(function ($query) use ($pipeline) {
            return $query->aggregate(
                $pipeline,
                ['allowDiskUse' => true]
            );
        });
        return $result;
    }

    public function match(array $conditions)
    {
        $match = [];
        foreach ($conditions as $condition) {
            $operator = $condition['condition'] ?? '';
            switch ($operator) {
                case '=':
                case 'is':
                    $match[] = [$condition['key'] => $condition['value']];
                    break;
                case 'in':
                    $match[] = [$condition['key'] => ['$in' => $condition['value']]];
                    break;
                case 'not_in':
                    $match[] = [$condition['key'] => ['$nin' => $condition['value']]];
                    break;
                case '!=':
                case 'not':
                    $match[] = [$condition['key'] => ['$ne' => $condition['value']]];
                    break;
                case 'greater':
                case '>':
                    $match[] = [$condition['key'] => ['$gt' => $this->format_date($condition)]];
                    break;
                case '>=':
                    $match[] = [$condition['key'] => ['$gte' => $this->format_date($condition)]];
                    break;
                case 'less':
                case '<':
                    $match[] = [$condition['key'] => ['$lt' => $this->format_date($condition)]];
                    break;
                case '<=':
                    $match[] = [$condition['key'] => ['$lte' => $this->format_date($condition)]];
                    break;
                case 'like':
                    $match[] = [$condition['key'] => ['$regex' => $condition['value']]];
                    break;
                case 'not_have':
                    $match[] = [$condition['key'] => ['$not' => ['$regex' => $condition['value']]]];
                    break;
                default:
                    $match[] = [$condition['key'] => $condition['value']];
            }
        }
        return $match;
    }

    protected function format_date($condition)
    {
        $value = $condition['value'];
        $type = !empty($condition['type']) ? $condition['type'] : '';
        switch ($type) {
            case 'datetime':
                $value = new \MongoDB\BSON\UTCDateTime(new \DateTime($value));
                break;
        }
        return $value;
    }

    public function project(array $select)
    {
        $project = [];
        foreach ($select as $value) {
            $project[$value] = 1;
        }
        return $project;
    }

    public function sort(array $data)
    {
        $sort = [];
        foreach ($data as $value) {
            $sort[$value['key']] = ($value['value'] == 'ASC') ? 1 : -1;
        }
        return $sort;
    }

    public function setValue(array $data, $prefix = '')
    {
        $set = [];
        foreach ($data as $value) {
            $set[$value] = '$' . $prefix . $value;
        }
        return $set;
    }

}
