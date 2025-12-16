<?php

namespace App\Console\Commands;

use App\Mail\LicenseExpiryReminder;
use App\Models\EmailLog;
use App\Models\License;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

use function Symfony\Component\Clock\now;

class CheckLicenseExpiry extends Command
{
    protected $signature = 'licenses:check-expiry';

    protected $description = 'Check license expiry dates and send email reminders';

    private const REMINDER_DAYS = [30, 7, 1, 0];

    public function handle(): int
    {
        $this->info('🔍 Checking license expiry dates...');
        $this->newLine();

        $this->updateAllLicenseStatuses();

        $emailsSent = 0;

        foreach (self::REMINDER_DAYS as $day) {
            $sent = $this->processRemindersForDay($day);
            $emailsSent += $sent;
        }

        $this->newLine();
        $this->info("✅ License expiry check completed!");
        $this->info("📧 Total email sent: {$emailsSent}");

        $this->displaySummary();

        return self::SUCCESS;
    }

    private function updateAllLicenseStatuses(): void {
        $this->info('📊 Updating license status...');

        $licenses = License::all();

        foreach ($licenses as $license) {
            $license->calculateMasaBerlaku();
            $license->updateStatus();
            $license->saveQuietly();
        }

        $this->info("✅ Updated {$licenses->count()} licenses");
        $this->newLine();
    }

    private function processRemindersForDay(int $day): int {
        $this->info("Processing reminders for H-{$day}...");

        $licenses = License::all()->filter(function ($license) use ($day) {
            return $license->needsReminderForDay($day);
        });

        if ($licenses->isEmpty()) {
            $this->comment("No licenses need reminder for H-{$day}");
            return 0;
        }

        $sent = 0;

        foreach ($licenses as $license) {
            try {
                Mail::to($license->email_notifikasi)
                    ->send(new LicenseExpiryReminder($license, $day));

                EmailLog::create([
                    'license_id' => $license->id,
                    'email' => $license->email_notifikasi,
                    'reminder_day' => $day,
                    'sent_at' => now(),
                ]);

                $this->line("✅ Email sent to {$license->email_notifikasi} for '{$license->nama_perizinan}'");
                $sent++;

            } catch (\Exception $e) {
                $this->error("❌ Failed to send email for '{$license->nama_perizinan}': {$e->getMessage()}");
            }
        }

        $this->info("📧 Sent {$sent} reminder(s) for H-{$day}");
        $this->newLine();

        return $sent;
    }

    private function displaySummary(): void {
        $this->newLine();
        $this->info('📈 License Summary:');
        $this->table(
            ['Status', 'Count'],
            [
                ['AKTIF', License::aktif()->count()],
                ['AKAN HABIS', License::akanHabis()->count()],
                ['KADALUARSA', License::kadaluarsa()->count()],
                ['TOTAL', License::count()],
            ]
        );
    }
}
