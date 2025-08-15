<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\PublisherController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;


Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Rotas que só o admin pode acessar
Route::middleware(['role:admin'])->group(function () {
    Route::resource('users', UserController::class); // admin pode editar papéis
});

// Rotas que admin e bibliotecário podem acessar
Route::middleware(['role:admin,bibliotecario'])->group(function () {
    Route::resource('books', BookController::class)->except(['index', 'show']);
    Route::resource('publishers', PublisherController::class)->except(['index', 'show']);
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('authors', AuthorController::class)->except(['index', 'show']);
});

// Rotas de visualização para todos os papéis
Route::middleware(['role:admin,bibliotecario,cliente'])->group(function () {
    Route::get('books', [BookController::class, 'index']);
    Route::get('books/{book}', [BookController::class, 'show']);
    // ...outras rotas de visualização...
});

// Rotas para criação de livros
Route::get('/books/create-id-number', [BookController::class, 'createWithId'])->name('books.create.id');
Route::post('/books/create-id-number', [BookController::class, 'storeWithId'])->name('books.store.id');

Route::get('/books/create-select', [BookController::class, 'createWithSelect'])->name('books.create.select');
Route::post('/books/create-select', [BookController::class, 'storeWithSelect'])->name('books.store.select');

// Rotas RESTful para index, show, edit, update, delete (tem que ficar depois das rotas /books/create-id-number e /books/create-select)
Route::resource('books', BookController::class)->except(['create', 'store']);

Route::resource('users', UserController::class)->except(['create', 'store', 'destroy']);

// Rota para registrar um empréstimo
Route::post('/books/{book}/borrow', [BorrowingController::class, 'store'])->name('books.borrow');

// Rota para listar o histórico de empréstimos de um usuário
Route::get('/users/{user}/borrowings', [BorrowingController::class, 'userBorrowings'])->name('users.borrowings');

// Rota para registrar a devolução
Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])->name('borrowings.return');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('usuarios', UserController::class);
});

Route::middleware(['auth', 'role:admin,bibliotecario'])->group(function () {
    Route::resource('books', BookController::class);
    Route::resource('publisher', PublisherController::class);
});

Route::middleware(['auth', 'role:cliente'])->group(function () {
    Route::get('books', [BookController::class, 'index']);
});

Route::middleware(['auth', 'role:bibliotecario,admin'])->group(function () {
    Route::post('users/{user}/clear-debit', [UserController::class, 'clearDebit'])->name('users.clearDebit');
});
