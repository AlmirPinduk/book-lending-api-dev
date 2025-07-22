<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use Illuminate\Http\Request;
use App\Services\LoanService;
use App\Exceptions\OutOfStockException;
use Illuminate\Http\JsonResponse;

class LoanController extends Controller
{
    protected LoanService $loanService;

    public function __construct(LoanService $loanService)
    {
        $this->loanService = $loanService;
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
        ]);

        try {
            $loan = $this->loanService->createLoan(
                $validated['book_id'],
                $validated['member_id']
            );

            return response()->json($loan, 201);

        } catch (OutOfStockException $e) {
            return response()->json(['message' => $e->getMessage()], 409);
        }
    }

    public function return(int $id): JsonResponse
    {
        $loan = Loan::findOrFail($id);

        if ($loan->returned_at) {
            return response()->json(['message' => 'Already returned.'], 400);
        }

        $updated = $this->loanService->returnLoan($loan);

        return response()->json($updated);
    }
}
