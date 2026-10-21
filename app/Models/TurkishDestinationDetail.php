<?php

namespace App\Models;

use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurkishDestinationDetail extends Model
{
    use \Bkwld\Cloner\Cloneable;
    protected $guarded = ['id'];
    protected $table = 'turkish_destination_details';

    public function destination()
    {
        return $this->belongsTo(TurkishDestination::class, 'turkish_destination_id', 'id');
    }
}
