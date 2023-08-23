<?php

namespace App\Modules\Employee\JournalDate\Service;

use App\Models\JournalDate;
use App\Services\BaseService;

class JournalDateService extends BaseService
{
    public function __construct(JournalDate $journalDate)
    {
        $this->model = $journalDate;
    }
}
