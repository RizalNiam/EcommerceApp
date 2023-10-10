<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponses;
use Illuminate\Support\Facades\DB;
use Validator;

class FavoriteController extends Controller
{
    use ApiResponses;

    public function set_favorite(Request $request) {
        DB::table('books')
            ->where('id', $request['book_id'])
            ->update([
                'favorite' => 1,
            ]);

        return $this->requestSuccess('book successfully added');
    }

    public function unset_favorite(Request $request) {
        DB::table('books')
            ->where('id', $request['book_id'])
            ->update([
                'favorite' => 0,
            ]);

        return $this->requestSuccess('book successfully removed');
    }

    function get_favorites() {
        $user = auth("api")->user();

        $rawData = DB::table('books')
        ->join('favorites', 'books.id', '=', 'favorites.book_id')
        ->select('books.title as title','books.description', 'books.photo', 'books.price', 'books.category','books.favorite', 'books.created_at', 'books.updated_at')
        ->where('favorites.user_id', '=', $user->id)
        ->get();      
        
        return $this->requestSuccessData('Success!', $rawData);
    }
}
