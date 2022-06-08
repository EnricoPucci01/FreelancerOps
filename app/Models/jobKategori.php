<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class jobKategori extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='kategori_job';
    protected $primaryKey='kategorijob_id';
    protected $keyType='integer';
    public $timestamps=true;
    const CREATED_AT='created_at';
    const UPDATED_AT='updated_at';
    const DELETED_AT='deleted_at';
    public $incrementing=true;
}
