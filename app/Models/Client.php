<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clients';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'country',
        'date',
        'status',
        'score',
        'company',
        'contact',
        'tel1',
        'tel2',
        'town',
        'area',
        'samples',
        'display',
        'prices',
        'brand',
        'comments',
        'email',
        'rank',
        'assigned',
        'created_at',
        'updated_at',
        'deleted_at',
        'status_id'
    ];
}
