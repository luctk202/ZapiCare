<?php

namespace App\Repositories\ProductCategory;

use App\Models\ProductCategory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class ProductCategoryRepository extends BaseRepository implements ProductCategoryInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    const HOT = 1;
    const HOME = 1;

    public function model()
    {
        // TODO: Implement model() method.
        return ProductCategory::class;
    }


    public function sort_parent($categories, $parent_id, $prefix, &$data)
    {
        foreach ($categories as $category) {
            if ($category['parent_id'] == $parent_id) {
                $category['prefix'] = $prefix;
                $data[] = $category;
                $child_prefix = $prefix . '---';
                $this->sort_parent($categories, $category['id'], $child_prefix, $data);
            }
        }
    }

    public function tree()
    {
        $categories = Cache::rememberForever('product_categories', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        $data = [];
        $this->sort_parent($categories, 0, '', $data);
        return $data;
    }

    public function child_ids($categories, $parent_id, &$ids){
        foreach ($categories as $category) {
            if ($category->parent_id == $parent_id) {
                $ids[] = $category->id;
                $this->child_ids($categories, $category->id, $ids);
            }
        }
    }

    public function get_child_id($parent_id){
        $categories = Cache::rememberForever('product_categories', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        $ids = [];
        $this->child_ids($categories, $parent_id, $ids);
        return $ids;
    }
}
