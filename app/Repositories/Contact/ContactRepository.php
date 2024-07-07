<?php


namespace App\Repositories\Contact;
use App\Models\Contact;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class ContactRepository extends BaseRepository implements ContactInterface
{
//
//    const STATUS_SHOW = 1;
//    const STATUS_HIDE = 0;
//
//    const HOT = 1;
//
//    public $status = [
//        self::STATUS_SHOW => 'Hiện',
//        self::STATUS_HIDE => 'Ẩn',
//    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Contact::class;
    }

//    public function active_all()
//    {
//        $brand = Cache::rememberForever('brand', function () {
//            return $this->get(['status' => self::STATUS_SHOW]);
//        });
//        return $brand;
//    }
}
