<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XenoClassSaleTime extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'xeno_class_sale_times';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $with = ['xenoClass:id,class'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'auction_id',
        'xeno_class_id',
        'start_time',
        'end_time',
    ];

    /**
     * Get the xenoClass that owns the XenoClassSaleTime
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function xenoClass(): BelongsTo
    {
        return $this->belongsTo(XenoClass::class, 'xeno_class_id', 'id');
    }
}