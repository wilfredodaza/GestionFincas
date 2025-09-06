<?php

namespace App\Models;


use CodeIgniter\Model;

class Notification extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'notifications';
    protected $returnType       = 'object';
}
