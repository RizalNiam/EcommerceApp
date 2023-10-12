<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use App\Models\Book;
use Validator;

class BookController extends Controller
{
    use ApiResponses;

    public function addbook(Request $request) {
        $validator = Validator::make(request()->all(), [
            'title' => 'string|max:255',
            'price' => 'string|max:255',
            'description' => 'string|max:2048',
            'photo' => 'image|file',
            'category' => 'string|max:255',
        ]);
        
        if ($validator->fails()) {
            return $this->responseValidation($validator->errors(), 'book failed to add');
        }

        $link = null;

        if ($request->file('photo') != null) {
            $path = $request->file('photo')->store('public', 'public');
            $link = "https://magang.crocodic.net/ki/RizalAfifun/EcommerceApp/storage/app/public/";
            $link .= $path;
        }

        DB::table('books')->insert([
            'title' => $request['title'],
            'price' => $request['price'],
            'description' => $request['description'],
            'photo' => $link,
            'category' => $request['category'],
        ]);

        return $this->requestSuccess('book successfully added');
    }

    function get_books() {

        $user = auth("api")->user();

        $books = Book::select('*')->get();

        $books->map(function ($books) {
           
            $favorite = $books->favorite === 1 ? true : false;
            $books->favorite = $favorite;

            return $books;
        });

        $rawData = DB::table('books')
        ->select('books.title as title','books.description', 'books.photo', 'books.price', 'books.category', 'favorite', 'books.created_at', 'books.updated_at')
        ->inRandomOrder()
        ->get();                                                                                
        
        return $this->requestSuccessData('Success!', $books);
    }

    function get_favorites() {
        $user = auth("api")->user();

        $rawData = DB::table('books')
        ->join('favorites', 'books.id', '=', 'favorites.book_id')
        ->join('users', 'favorites.user_id', '=', 'users.id')
        ->select('users.username as user', 'books.name as title','books.description', 'books.photo', 'books.price', 'books.category', 'books.created_at', 'books.updated_at')
        ->where('favorites.user_id', '=', $user->id)
        ->get();        
        
        return $this->requestSuccessData('Success!', $rawData);
}

    public function delete_image()
    {
        $user = auth('api')->user();

        $file = storage_path('/app/public/public') . $user->photo;

        if (file_exists($file)) {
            @unlink($file);
        }

        $user->delete;
    }
}
