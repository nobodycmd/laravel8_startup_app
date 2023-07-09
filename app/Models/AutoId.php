<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\AutoId
 *
 * @property int $id
 * @property int $t
 * @method static \Illuminate\Database\Eloquent\Builder|AutoId newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoId newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoId query()
 * @method static \Illuminate\Database\Eloquent\Builder|AutoId whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AutoId whereT($value)
 * @mixin \Eloquent
 */
class AutoId extends Model
{
    use HasFactory;

    public $table = 'auto_id';

    public $timestamps = false;
}
