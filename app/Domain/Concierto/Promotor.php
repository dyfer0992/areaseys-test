<?php

namespace App\Domain\Concierto;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Promotor extends Model
{
    use Notifiable;

    protected $table = 'promotores';

    public $timestamps = false;
}
