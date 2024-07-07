<?php


namespace App\Repositories\ContactForm;

use App\Models\ContactForm;
use App\Repositories\Base\BaseRepository;
class ContactFormRepository extends BaseRepository implements ContactFormInterface
{
    public function model()
    {
        // TODO: Implement model() method.
        return ContactForm::class;
    }
}
