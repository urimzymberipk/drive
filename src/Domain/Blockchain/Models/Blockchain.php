<?php

namespace Domain\Blockchain\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Blockchain extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_id',
        'txLink',
        'cerLink',
        'fileHash',
        'folderLocation',
    ];

}
