<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Advertisement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\URL;


class HomeController extends Controller
{
    
    public function index(Request $request)
    {


        //SEARCH QUERIES
        $searchquery_date = $request->input('date');
        $searchquery_title = $request->input('title');
        $searchquery_orderBy = 'ASC'; //default order by ascending



        //overide order by if filter is set
        if (!empty($searchquery_orderBy) && $searchquery_orderBy) {
            $searchquery_orderBy = $request->input('order-by');
        }

      

        $advertisements = Advertisement::orderBy('title', 'ASC')
            ->where([
                ['visible', '=', 'Y'],
                ['title', 'LIKE', "%{$searchquery_title}%"]
            ])
            ->paginate(15)->toArray();

        $advertisements['links'] = HelperController::paginator($advertisements);
 
        $results = [];

        foreach ($advertisements['data'] as $ad) {

            //APPLY SEARCH FILTERS:: DATE
            if (!empty($searchquery_date) && $searchquery_date) {
            }

            //APPLY SEARCH FILTERS: TITLE
            if (!empty($searchquery_title) && $searchquery_title) {
            }

            $results[] = [
                "created_at" => "2021-08-24T17:18:49.000000Z",
                "title"       => $ad["title"],
                "description" => $ad["description"],
                "photo_1"     => $ad["photo_1"] ? asset('storage' . $ad["photo_1"]) : null,
                "photo_2"     => $ad["photo_2"] ? asset('storage' . $ad["photo_2"]) : null,
                "photo_3"     => $ad["photo_3"] ? asset('storage' . $ad["photo_3"]) : null,
                "uuid"        => $ad["uuid"],
            ];
        }
        
        $data = [
            'ads' => $results,
            'advertisements' => $advertisements,
        ];



        return view('home', ['data' => $data]);
    }
}
