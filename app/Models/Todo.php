<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'created_at',
        'updated_at',
        'name',
        'description',
        // 白名單
        // 複寫Laravel原先create的設定
        // 這些欄位可以被批量寫入
    ];
    
    protected $guarded = [
        // 黑名單
        // 複寫Laravel原先create的設定
        // 除這些欄位外，其他欄位皆可以被批量寫入
    ];
}
