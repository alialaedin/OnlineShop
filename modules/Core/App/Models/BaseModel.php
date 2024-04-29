<?php

namespace Modules\Core\App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseModel
 * @package Modules\Core\Entities
 * @method static create($attributes)
 * @method static findOrFail($id)
 * @method static find($id)
 * @method static Builder dateFilter()
 * @method static Builder sortFilter()
 * @method static Builder searchFilters()
 * @method static Builder filters()
 * @property array  withCommonRelations()
 * @property @protected @static array  $commonRelations; // should be static
 */
abstract class BaseModel extends Model
{
    use BaseModelTrait;
}
