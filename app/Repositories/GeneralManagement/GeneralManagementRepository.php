<?php

namespace App\Repositories\GeneralManagement;

use App\Models\Page;

use App\Repositories\Base\BaseRepository;

class GeneralManagementRepository extends BaseRepository implements GeneralManagementInterface
{

    public function model()
    {
        return Page::class;
    }

}
