<?php

namespace App\Repositories\Fee;

use App\Models\Filter;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class FeeRepository extends BaseRepository implements FeeInterface
{

    public function model()
    {
        // TODO: Implement model() method.
//        return Filter::class;
    }

//    public function active_all()
//    {
//        //return $this->get();
//        $data = Cache::rememberForever('filters', function () {
//            $data =  $this->all();
//            $data->load('attributes');
//            return $data;
//        });
//        return $data;
//    }
}
