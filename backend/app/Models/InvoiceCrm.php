<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceCrm extends Model
{
    protected $table = 'invoices_crm';
    protected $guarded = ['id'];
    protected $casts = ['items' => 'array', 'due_date' => 'date', 'paid_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function contact(): BelongsTo { return $this->belongsTo(Contact::class); }
    public function scopeForUser($q, $userId) { return $q->where('user_id', $userId); }
}
