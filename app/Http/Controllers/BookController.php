<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\JsonResponse;

class BookController extends Controller
{
    /**
     * Liston të gjitha librat me gjendje të kopjeve
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return response()->json(Book::all());
    }
}
