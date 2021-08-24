<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        //SEARCH QUERIES
        $searchquery_date = $request->input('date');
        $searchquery_title = $request->input('title');
        $searchquery_orderBy = 'ASC'; //default order by ascending

        //overide order by if filter is set
        if(!empty($searchquery_orderBy) && $searchquery_orderBy){
            $searchquery_orderBy = $request->input('order-by'); 
        }
        

        $advertisements = Advertisement::orderBy('title', 'ASC')
        ->where([
            ['visible', '=', 'Y'],
            ['user_id', '=', Auth::user()->id],
            ['title', 'LIKE', "%{$searchquery_title}%"]
        ])
        ->paginate(15)->toArray();

        $advertisements['links'] = HelperController::paginator($advertisements);
        

        

        $results = [];

        foreach($advertisements['data'] as $ad){

            //APPLY SEARCH FILTERS:: DATE
            if(!empty($searchquery_date) && $searchquery_date){

            }

            //APPLY SEARCH FILTERS: TITLE
            if(!empty($searchquery_title) && $searchquery_title){

            }

            $results [] = $ad;
           
        }


        $data = [
            'ads' => $results,
            'advertisements' => $advertisements,
        ];

    

        return view('advertisement-index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('advertisement-add');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        //
        $data = [
            'title'       => $advertisement->title,
            'description' => $advertisement->description,
            'photo_1'     => $advertisement->photo_1,
            'photo_2'     => $advertisement->photo_2,
            'photo_3'     => $advertisement->photo_3,
            'uuid'        => $advertisement->uuid,
        ];

        return view('advertisement-show', ['data' => $data]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertisement $advertisement)
    {
        //
        $data = [
            'title'       => $advertisement->title,
            'description' => $advertisement->description,
            'photo_1'     => $advertisement->photo_1,
            'photo_2'     => $advertisement->photo_2,
            'photo_3'     => $advertisement->photo_3,
            'uuid'        => $advertisement->uuid,
        ];

        return view('advertisement-edit', ['data' => $data]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        echo "deleting Ad";

        dd($advertisement);
        if(!$advertisement){
            return redirect()->back();
        }


    }
}
