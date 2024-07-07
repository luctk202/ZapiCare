<?php

namespace App\Repositories\TagLabel;

use App\Models\TagLabel;
use App\Repositories\Base\BaseRepository;

class TagLabelRepository extends BaseRepository implements TagLabelInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return TagLabel::class;
    }

}
