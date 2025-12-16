<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class License extends Model
{
    protected $fillable = [
        'nama_perizinan',
        'jenis_perizinan',
        'instansi_penerbit',
        'nomor_izin',
        'tanggal_terbit',
        'tanggal_berakhir',
        'masa_berlaku_hari',
        'penanggung_jawab',
        'email_notifikasi',
        'status'
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_berakhir' => 'date',
        'masa_berlaku_hari' => 'integer'
    ];

    protected static function booted(): void
    {
        static::saving(function (License $license) {
            $license->calculateMasaBerlaku();
            $license->updateStatus();
        });
    }

    public function emailLogs(): HasMany {
        return $this->hasMany(EmailLog::class);
    }

    public function calculateMasaBerlaku(): void {
        if ($this->tanggal_berakhir) {
            $this->masa_berlaku_hari = Carbon::now()->diffInDays(
                $this->tanggal_berakhir,
                false
            );
        }
    }

    public function updateStatus(): void {
        $daysRemaining = $this->masa_berlaku_hari;

        if ($daysRemaining < 0) {
            $this->status = 'KADALUARSA';
        } elseif ($daysRemaining <= 30) {
            $this->status = 'AKAN HABIS';
        } else {
            $this->status = 'AKTIF';
        }
    }

    public function scopeAktif($query) {
        return $query->where('status', 'AKTIF');
    }

    public function scopeAkanHabis($query) {
        return $query->where('status', 'AKAN HABIS');
    }

    public function scopeKadaluarsa($query) {
        return $query->where('status', 'KADALUARSA');
    }

    public function needsReminderForDay(int $day): bool {
        $daysRemaining = Carbon::now()->diffInDays($this->tanggal_berakhir, false);

        if ($daysRemaining != $day) {
            return false;
        }

        $today = Carbon::today();

        return !$this->emailLogs()->where('reminder_day', $day)
                    ->whereDate('sent_at', $today)
                    ->exists();
    }

    public function getStatusColorAttribute(): string {
        return match ($this->status) {
            'AKTIF' => 'success',
            'AKAN HABIS' => 'warning',
            'KADALUARSA' => 'danger',
            default => 'secondary'
        };
    }
}
