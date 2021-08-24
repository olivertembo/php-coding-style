<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $advertisements = Advertisement::orderBy('title', 'ASC')
        ->where('visible', '=', 'Y')
        ->get()->toArray();

        //SEARCH QUERIES
        $searchquery_date = $request->input('date');
        $searchquery_title = $request->input('title');
        $searchquery_orderBy = $request->input('order-by');

        $results = [];

        foreach($advertisements as $ad){

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
        ];


        
        echo "List all Products";

        dd($advertisements);

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
