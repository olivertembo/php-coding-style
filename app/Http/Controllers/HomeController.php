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
        $searchquery_orderBy = $request->input('order-by'); //default order by ascending


        //overide order by if filter is set
        if (empty($searchquery_orderBy) || !$searchquery_orderBy) {
            $searchquery_orderBy = 'ASC';
        }

        //The datepicker's month starts from index zero instead of one
        if (!empty($searchquery_date) && $searchquery_date) {
            $time = strtotime($searchquery_date);
            $searchquery_date = date("Y-m-d", strtotime("+1 month", $time));
        }

        $advertisements = Advertisement::orderBy('id', $searchquery_orderBy)
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
                if ($searchquery_date !== date('Y-m-d', (strtotime($ad['created_at'])))) continue;
            }

            $results[] = [
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
            'query' => [
                'date' => $searchquery_date,
                'title' => $searchquery_title,
                'orderBy' => $searchquery_orderBy,
            ]
        ];

        return view('home', ['data' => $data]);
    }

    public function show(Advertisement $advertisement)
    {

        $data = [
            'title'       => $advertisement->title,
            'description' => $advertisement->description,
            'photo_1'     => $advertisement->photo_1 ? asset('storage' . $advertisement->photo_1) : null,
            'photo_2'     => $advertisement->photo_2 ? asset('storage' . $advertisement->photo_2) : null,
            'photo_3'     => $advertisement->photo_3 ? asset('storage' . $advertisement->photo_3) : null,
            'uuid'        => $advertisement->uuid,
        ];

        return view('pages.guest.advertisement-show', ['data' => $data]);
    }
}
