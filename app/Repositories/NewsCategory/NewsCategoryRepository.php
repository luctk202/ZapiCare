<?php

namespace App\Repositories\NewsCategory;

use App\Models\NewsCategory;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class NewsCategoryRepository extends BaseRepository implements NewsCategoryInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];


    public function model()
    {
        // TODO: Implement model() method.
        return NewsCategory::class;
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
        $categories = Cache::rememberForever('news_categories', function () {
            //return $this->get(['status' => self::STATUS_ACTIVE]);
            return $this->get();
        });
        $data = [];
        $this->sort_parent($categories, 0, '', $data);
        return $data;
    }

    public function tree_active()
    {
        $categories = Cache::rememberForever('news_categories_active', function () {
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
        $categories = Cache::rememberForever('news_categories_active', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        $ids = [];
        $this->child_ids($categories, $parent_id, $ids);
        return $ids;
    }
}
