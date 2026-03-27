<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Conversion extends Model
{
    protected $fillable = ['user_id', 'input_type', 'output_type', 'status', 'input_data', 'output_files', 'project_id'];
    protected $casts = ['output_files' => 'array'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
