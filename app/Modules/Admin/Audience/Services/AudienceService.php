<?php

namespace App\Modules\Admin\Audience\Services;

use App\Services\BaseService;
use App\Traits\Authorizable;
use App\Models\Audience;

class AudienceService extends BaseService
{
    use Authorizable;

    public function __construct(Audience $audience)
    {
        $this->model = $audience;
    }

}
