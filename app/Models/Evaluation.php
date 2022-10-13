<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    use HasFactory;

    // especifica o nome da tabela
    protected $table = 'order_evaluations';

    protected $fillable = ['order_id', 'client_id', 'stars', 'comment'];

    // relacionamento many to one
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // relacionamento many to one
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
