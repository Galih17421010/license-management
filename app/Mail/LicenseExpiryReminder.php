<?php

namespace App\Mail;

use App\Models\License;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LicenseExpiryReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public License $license,
        public int $reminderDay
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->getSubject();

        return new Envelope(
            subject: $subject
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.license-expiry-reminder',
            with: [
                'license' => $this->license,
                'reminderDay' => $this->reminderDay,
                'urgencyLevel' => $this->getUrgencyLevel(),
                'actionText' => $this->getActionText()
            ]
        );
    }

    private function getSubject(): string {
        return match ($this->reminderDay) {
            30 => "Reminder: Perizinan {$this->license->nama_perizinan} akan berakhir dalam 30 hari",
            7 => "Urgent: Perizinan {$this->license->nama_perizinan} akan berakhir dalam 7 hari",
            1 => "Sangat Urgent: Perizinan {$this->license->nama_perizinan} akan berakhir BESOK",
            0 => "KRITIS: Perizinan {$this->license->nama_perizinan} akan berakhir HARI INI",
            default => "Reminder: Perizinan {$this->license->nama_perizinan}"
        };
    }

    private function getUrgencyLevel(): string {
        return match ($this->reminderDay) {
            30 => 'info',
            7 => 'warning',
            1, 0 => 'danger',
            default => 'info'
        };
    }

    private function getActionText(): string {
        return match ($this->reminderDay) {
            30 => 'Harap segera mempersiapkan proses perpanjangan perizinan.',
            7 => 'Segera lakukan perpanjangan perizinan untuk menghindari kadaluarsa.',
            1 => 'Segera perpanjang perizeinan ini sebelum BESOK!',
            0 => 'PERIZINAN INI BERAKHIR HARI INI! Segera lakukan tindakan.',
            default => 'Harap perhatikan masa berlaku perizinan.'
        };
    }
}
