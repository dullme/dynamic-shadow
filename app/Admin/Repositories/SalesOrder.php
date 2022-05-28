<?php

namespace App\Admin\Repositories;

use App\Models\SalesOrder as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class SalesOrder extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
