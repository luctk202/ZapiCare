<?php

namespace App\Repositories\PartnerSetting;

use App\Models\Partner;
use App\Models\PartnerSetting;
use App\Repositories\Base\BaseRepository;

class PartnerSettingRepository extends BaseRepository implements PartnerSettingInterface
{


    public function model()
    {
        // TODO: Implement model() method.
        return PartnerSetting::class;
    }

}
