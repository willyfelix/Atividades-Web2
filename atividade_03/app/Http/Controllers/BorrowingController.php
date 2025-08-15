<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing; 

class BorrowingController extends Controller
{
   public function store(Request $request, Book $book)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Verifica se o livro já está emprestado e não devolvido
        $emprestimoAberto = Borrowing::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if ($emprestimoAberto) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Este livro já está emprestado.');
        }

        // Verifica se o usuário já tem 5 livros emprestados e não devolvidos
        $emprestimosUsuario = Borrowing::where('user_id', $request->user_id)
            ->whereNull('returned_at')
            ->count();

        if ($emprestimosUsuario >= 5) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Limite de 5 livros emprestados atingido.');
        }

        $user = User::find($request->user_id);
        if ($user->debit > 0) {
            return redirect()->route('books.show', $book)
                ->with('error', 'Você possui débito em aberto. Regularize antes de novos empréstimos.');
        }

        Borrowing::create([
            'user_id' => $request->user_id,
            'book_id' => $book->id,
            'borrowed_at' => now(),
        ]);

        return redirect()->route('books.show', $book)->with('success', 'Empréstimo registrado com sucesso.');
    }

    public function returnBook(Borrowing $borrowing)
    {
        $diasEmprestimo = $borrowing->borrowed_at->diffInDays(now());
        $multa = 0;

        if ($diasEmprestimo > 15) {
            $diasAtraso = $diasEmprestimo - 15;
            $multa = $diasAtraso * 0.5;

            // Atualiza o débito do usuário
            $user = $borrowing->user;
            $user->debit += $multa;
            $user->save();
        }

        $borrowing->update([
            'returned_at' => now(),
        ]);

        return redirect()->route('books.show', $borrowing->book_id)
            ->with('success', $multa > 0 ? "Devolução registrada com multa de R$ " . number_format($multa, 2, ',', '.') : 'Devolução registrada com sucesso.');
    }

    public function userBorrowings(User $user)
    {
        $borrowings = $user->books()->withPivot('borrowed_at', 'returned_at')->get();

        return view('users.borrowings', compact('user', 'borrowings'));
    }

}
