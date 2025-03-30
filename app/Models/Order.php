<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @SWG\Definition(
 *  definition="Order",
 *  @SWG\Property(
 *      property="id",
 *      type="bigint"
 *  ),
 *  @SWG\Property(
 *      property="name",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="email",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="user_id",
 *      type="bigint"
 *  ),
 *  @SWG\Property(
 *      property="status",
 *      type="enum('Active', 'Resolved')"
 *  ),
 *  @SWG\Property(
 *      property="message",
 *      type="string"
 *  ),
 *  @SWG\Property(
 *      property="comment",
 *      type="string"
 *  )
 * )
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'status', 'message', 'name', 'email', 'comment'];

    public function user() : belongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
