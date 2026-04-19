<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'surat_masuk';

    protected $fillable = [
        'user_id',
        'nomor_surat',
        'tanggal_surat',
        'tanggal_terima',
        'pengirim',
        'jenis_surat',
        'perihal',
        'file_surat',
        'file_gabungan',
        'barcode',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_surat' => 'date',
            'tanggal_terima' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function disposisis(): HasMany
    {
        return $this->hasMany(Disposisi::class);
    }
}
