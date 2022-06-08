<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class sertifikat extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table='sertifikat';
    protected $primaryKey='sertifikat_id';
    protected $keyType='integer';
    public $timestamps=true;
    const CREATED_AT='created_at';
    const UPDATED_AT='updated_at';
    const DELETED_AT='deleted_at';
    public $incrementing=true;
}
