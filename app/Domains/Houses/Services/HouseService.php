<?php
namespace App\Domains\Categories\Services;

use App\Core\Services\BaseService;
use App\Domains\Categories\Repositories\HouseRepository;

class HouseService extends BaseService
{
    public function __construct(HouseRepository $repo)
    {
        $this->setRepo($repo);
    }
}