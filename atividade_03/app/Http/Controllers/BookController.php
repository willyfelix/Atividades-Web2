<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Book;
use App\Models\Publisher;
use App\Models\Author;
use App\Models\Category;
use App\Models\User;

class BookController extends Controller
{
    // Formulário com input de ID
    public function createWithId()
    {
        return view('books.create-id');
    }

    // Salvar livro com input de ID
    public function storeWithId(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['title', 'isbn', 'published_year', 'publisher_id', 'author_id', 'category_id']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_' . str_replace(' ', '_', $coverImage->getClientOriginalName());
            
            // Criar diretório se não existir
            if (!Storage::exists('public/covers')) {
                Storage::makeDirectory('public/covers');
            }
            
            $coverImage->storeAs('public/covers', $coverImageName);
            $data['cover_image'] = $coverImageName;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    // Formulário com input select
    public function createWithSelect()
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.create-select', compact('publishers', 'authors', 'categories'));
    }

    // Salvar livro com input select
    public function storeWithSelect(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['title', 'isbn', 'published_year', 'publisher_id', 'author_id', 'category_id']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_' . str_replace(' ', '_', $coverImage->getClientOriginalName());
            
            // Criar diretório se não existir
            if (!Storage::exists('public/covers')) {
                Storage::makeDirectory('public/covers');
            }
            
            $coverImage->storeAs('public/covers', $coverImageName);
            $data['cover_image'] = $coverImageName;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Livro criado com sucesso.');
    }

    public function edit(Book $book)
    {
        $publishers = Publisher::all();
        $authors = Author::all();
        $categories = Category::all();

        return view('books.edit', compact('book', 'publishers', 'authors', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20',
            'published_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'publisher_id' => 'required|exists:publishers,id',
            'author_id' => 'required|exists:authors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->only(['title', 'isbn', 'published_year', 'publisher_id', 'author_id', 'category_id']);

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old cover image if exists
            if ($book->cover_image && Storage::exists('public/covers/' . $book->cover_image)) {
                Storage::delete('public/covers/' . $book->cover_image);
            }

            $coverImage = $request->file('cover_image');
            $coverImageName = time() . '_' . str_replace(' ', '_', $coverImage->getClientOriginalName());
            
            // Criar diretório se não existir
            if (!Storage::exists('public/covers')) {
                //Storage::makeDirectory('public/covers');
                echo "retrono:" . Storage::makeDirectory('public/covers');
                die();
            }
          
            $coverImage->storeAs('public/covers', $coverImageName);
           
            $data['cover_image'] = $coverImageName;
        }
        dd($data);
        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Livro atualizado com sucesso.');
    }

    public function show(Book $book)
    {
        // Carregando autor, editora e categoria do livro com eager loading
        $book->load(['author', 'publisher', 'category']);

        // Carregar todos os usuários para o formulário de empréstimo
        $users = User::all();

        return view('books.show', compact('book', 'users'));
    }

    public function index()
    {
        // Carregar os livros com autores, editoras e categorias usando eager loading e paginação
        $books = Book::with(['author', 'publisher', 'category'])->paginate(20);

        return view('books.index', compact('books'));
    }

    public function destroy(Book $book)
    {
        // Delete cover image if exists
        if ($book->cover_image && Storage::exists('public/covers/' . $book->cover_image)) {
            Storage::delete('public/covers/' . $book->cover_image);
        }

        $book->delete();

        return redirect()->route('books.index')->with('success', 'Livro deletado com sucesso.');
    }
}