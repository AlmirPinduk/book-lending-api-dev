<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Exceptions\OutOfStockException;

class LoanService
{
    public function createLoan(int $bookId, int $memberId): Loan
    {
        return DB::transaction(function () use ($bookId, $memberId) {
            $book = Book::lockForUpdate()->findOrFail($bookId);

            if ($book->available_copies < 1) {
                throw new OutOfStockException('No copies available.');
            }

            $book->decrement('available_copies');

            return Loan::create([
                'book_id' => $bookId,
                'member_id' => $memberId,
                'loaned_at' => now(),
                'due_at' => Carbon::now()->addDays(14),
            ]);
        });
    }

    public function returnLoan(Loan $loan): Loan
    {
        return DB::transaction(function () use ($loan) {
            $loan->returned_at = now();
            $loan->save();

            $loan->book->increment('available_copies');

            return $loan;
        });
    }
}
