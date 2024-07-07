<?php

namespace App\Repositories\User;

use App\Models\User;
use App\Repositories\Base\BaseRepository;

class UserRepository extends BaseRepository implements UserInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    const TYPE_PRODUCER = 1;
    const TYPE_SALE = 2;
    const TYPE_CTV = 3;
    const TYPE_CUSTOMER = 4;

    const VERIFIED = 1;
    const NOT_VERIFY = 0;

    const GROUP_NPP = 4;
    const GROUP_KH = 6;

    public function model()
    {
        // TODO: Implement model() method.
        return User::class;
    }

    public function child_ids($parent_ids, &$child_ids){
        $childs = $this->pluckWhere(['parent_id' => ['parent_id', 'whereIn', $parent_ids], 'type' => self::TYPE_CTV, 'status' => self::STATUS_ACTIVE, 'verified' => self::VERIFIED], 'id')->toArray();
        if($childs){
            $child_ids = array_merge($child_ids, $childs);
            $this->child_ids($childs, $child_ids);
        }
        //return $child_ids;
    }

    public function parent_ids($id, &$parent_ids)
    {
        if($id > 0){
            $parents = $this->find($id);
            if ($parents) {
                $parent_ids[] = $id;
                $this->parent_ids($parents->parent_id, $parent_ids);
            }
        }
    }
}
