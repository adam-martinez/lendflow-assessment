<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\BestSellersRequest;
use Illuminate\Support\Facades\Http;

class BestSellers extends Controller
{
    public function __invoke(BestSellersRequest $request)
    {
        $query = collect([
            'api-key' => config('services.nyt.key'),
            'author' => $request->author,
            'isbn' => $request->isbn,
            'title' => $request->title,
            'offset' => $request->offset,
        ])
        ->filter(); // Remove empty values

        $query_string = http_build_query($query->toArray(), '', '&', PHP_QUERY_RFC3986); // Encode and concat
        $query_string = str('?')->append($query_string)->replace('%3B', ';'); // Restore semicolons

        $names = Http::get(config('services.nyt.url') . $query_string);

        return $names->json();
    }
}
