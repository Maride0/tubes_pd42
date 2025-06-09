<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BeritaController extends Controller
{
    public function index()
    {
        $apiKey = env('SPOONACULAR_API_KEY');

        $response = Http::get('https://api.spoonacular.com/recipes/random', [
            'apiKey' => $apiKey,
            'number' => 5
        ]);

        $data = json_decode($response);
        $hasil = [];

        if (isset($data->recipes)) {
            foreach ($data->recipes as $row) {
                $hasil[] = [
                    'judul' => $row->title,
                    'url' => $row->sourceUrl,
                    'gambar' => $row->image,
                ];
            }
        }

        return view('berita', compact('hasil'));
    }
}