<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    // Tugas Mandiri J.1: Tarif denda diubah menjadi Rp2.000 per hari
    public const DENDA_PER_HARI = 2000;

    protected $fillable = [
        'book_id',
        'member_id',
        'tanggal_pinjam',
        'tanggal_kembali_rencana',
        'tanggal_kembali',
        'status',
        'denda',
    ];

    protected $appends = ['hari_terlambat', 'perkiraan_denda'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    protected function hariTerlambat(): Attribute
    {
        return Attribute::make(get: function () {
            $jatuhTempo = Carbon::parse($this->tanggal_kembali_rencana)->startOfDay();
            $acuan = $this->tanggal_kembali
                ? Carbon::parse($this->tanggal_kembali)->startOfDay()
                : today();

            if ($acuan->lessThanOrEqualTo($jatuhTempo)) {
                return 0;
            }

            return (int) $jatuhTempo->diffInDays($acuan);
        });
    }

    protected function perkiraanDenda(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->hari_terlambat * self::DENDA_PER_HARI
        );
    }
}