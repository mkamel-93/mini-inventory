<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method static Builder<static>|BaseModel newModelQuery()
 * @method static Builder<static>|BaseModel newQuery()
 * @method static Builder<static>|BaseModel query()
 *
 * @mixin \Eloquent
 */
abstract class BaseModel extends Model
{
    protected $perPage = 5;
}
