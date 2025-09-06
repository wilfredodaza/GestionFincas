<?php


namespace App\Models;


use CodeIgniter\Model;

class Menu extends Model
{
    protected $primaryKey = 'id';
    protected $table = 'menus';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'option',
        'url',
        'icon',
        'position',
        'type',
        'references',
        'status',
        'component',
        'title',
        'description',
        'table',
    ];

    protected $returnType       = 'object';
}