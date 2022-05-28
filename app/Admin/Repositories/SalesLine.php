<?php

namespace App\Admin\Repositories;

use App\Models\SalesLine as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class SalesLine extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
