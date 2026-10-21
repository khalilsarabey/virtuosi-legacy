<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishSection extends Model
{
    use \Bkwld\Cloner\Cloneable;
    protected $cloneable_relations = ['destinations'];
    protected $guarded = ['id'];

    public function destinations()
    {
        return $this->hasMany(TurkishDestination::class);
    }
}
