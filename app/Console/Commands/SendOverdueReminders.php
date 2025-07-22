<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Loan;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendOverdueReminders extends Command
{
    protected $signature = 'reminders:send-overdue';

    protected $description = 'Kontrollon huazimet e vonuara dhe logon një kujtesë për çdo anëtar';

    public function handle(): int
    {
        $overdueLoans = Loan::with('member', 'book')
            ->whereNull('returned_at')
            ->where('due_at', '<', Carbon::now())
            ->get();

        foreach ($overdueLoans as $loan) {
            Log::info("REMINDER: Member '{$loan->member->name}' ({$loan->member->email}) has an overdue book '{$loan->book->title}' due at {$loan->due_at}");
        }

        $this->info("U dërguan " . $overdueLoans->count() . " kujtesa për huazime të vonuara.");
        return Command::SUCCESS;
    }
}
