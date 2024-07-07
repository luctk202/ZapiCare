<?php

namespace App\Repositories\Filter;

use App\Models\Filter;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class FilterRepository extends BaseRepository implements FilterInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return Filter::class;
    }

    public function active_all()
    {
        //return $this->get();
        $data = Cache::rememberForever('filters', function () {
            $data =  $this->all();
            $data->load('attributes');
            return $data;
        });
        return $data;
    }
}
