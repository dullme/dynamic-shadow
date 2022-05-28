<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class SalesOrder extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'sales_orders';
    
}
