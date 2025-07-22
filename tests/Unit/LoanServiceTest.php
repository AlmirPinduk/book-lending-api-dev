<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Services\LoanService;
use App\Exceptions\OutOfStockException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoanServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LoanService $loanService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->loanService = new LoanService();
    }

    /** @test */
    public function it_creates_a_loan_and_decrements_book_copies()
    {
        $book = Book::create([
            'title' => 'Test Book',
            'author' => 'Author',
            'isbn' => '123456789',
            'available_copies' => 2,
        ]);

        $member = Member::create([
            'name' => 'Test Member',
            'email' => 'member@example.com',
            'membership_date' => now(),
        ]);

        $loan = $this->loanService->createLoan($book->id, $member->id);

        $this->assertInstanceOf(Loan::class, $loan);
        $this->assertEquals($book->id, $loan->book_id);
        $this->assertEquals($member->id, $loan->member_id);
        $this->assertEquals(1, Book::find($book->id)->available_copies);
    }

    /** @test */
    public function it_throws_exception_if_no_available_copies()
    {
        $book = Book::create([
            'title' => 'Unavailable Book',
            'author' => 'Author',
            'isbn' => '987654321',
            'available_copies' => 0,
        ]);

        $member = Member::create([
            'name' => 'Blocked Member',
            'email' => 'blocked@example.com',
            'membership_date' => now(),
        ]);

        $this->expectException(OutOfStockException::class);

        $this->loanService->createLoan($book->id, $member->id);
    }
}
