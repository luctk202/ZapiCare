<?php


namespace App\Repositories\TestSystem;

use App\Models\TestSystem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class TestSystemRepository extends BaseRepository implements TestSystemInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;
    public $status = [
        self::STATUS_ACTIVE => 'Kich hoạt',
        self::STATUS_BLOCK => 'Không kích hoạt',
    ];


    public function model()
    {
        // TODO: Implement model() method.
        return TestSystem::class;
    }
    public function getActiveTestSystems()
    {
        return Cache::rememberForever('test_system', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
    }
    public function active_all()
    {
        $test_system = Cache::rememberForever('test_system', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        return $test_system;
    }
}
