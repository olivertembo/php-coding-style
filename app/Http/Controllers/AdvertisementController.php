<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\URL;

class AdvertisementController extends Controller
{

    public function FilePondSingleImageUpload($floor_plan, $location = "./images/uploads", $disk = 'public')
    {
        //Floor Plan is an arrayt with two images with common meta deta

        //get Image data as an array

        //var_dump($floor_plan['data']);

        if (count($floor_plan['data']) && count($floor_plan['data']) > 0) {
            //there are more than two images as predicted
            //we will get the larger image, which is the last image image
            foreach ($floor_plan['data'] as $floor_plan_data) {
                $data = $floor_plan_data['data'];
            }
            $name = $location . '/' . uniqid() . '.jpg';

            $stored = Storage::disk($disk)->put($name, base64_decode($data));

            if ($stored) {
                return $name;
            } else {
                //something went wrong;
                return null;
            }
        } else {
            return null;
        }



        return null;
    }

    public function FilePondGalleryImagesUpload($gallery_images, $location = "/images/uploads", $disk = 'public')
    {

        //Do a number if tests to make sure the image exisit
        //if it fails, return null,
        //null will be writtetn to the database

        //There needs to be a minimum of 1 image in the array
        if (is_array($gallery_images) && count($gallery_images) > 0) {
            $images = [];
            foreach ($gallery_images as $gallery_image) {
                $single_image = json_decode($gallery_image, true);


                foreach ($single_image['data'] as $single_image) {
                    $data = $single_image['data'];
                }
                $name = $location . '/' . uniqid() . '.jpg';

                $stored = Storage::disk($disk)->put($name, base64_decode($data));

                if ($stored) {
                    $images[] = $name;
                }
            }

            return $images;
        } else {
            return null;
        }


        return true;
    }

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
        if (!empty($searchquery_orderBy) && $searchquery_orderBy) {
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


        $validated = $request->validate([
            'title'               => ['required', 'string', 'max:225'],
            'description'       => ['required', 'string', 'max:1024'],
            'filepond'         => ['required'],
        ]);

        $filepond_images = $request->input('filepond');

      


        //This returns an array if images locations in storage that have to be returned
        //These are NOT in the public folder
        $gallery_images = $this->FilePondGalleryImagesUpload($filepond_images, "/images/uploads", 'public');


        $photo_1 = null;
        if (isset($gallery_images[0])) {
            $photo_1 = $gallery_images[0];
        }

        $photo_2 = null;
        if (isset($gallery_images[1])) {
            $photo_2 = $gallery_images[1];
        }

        $photo_3 = null;
        if (isset($gallery_images[2])) {
            $photo_3 = $gallery_images[2];
        }

        


        $advertisement = new Advertisement;

        $advertisement->uuid = HelperController::generateUniquePublicId('advertisements', 'uuid');
        $advertisement->title = $request->input('title');
        $advertisement->description = $request->input('title');
        $advertisement->photo_1 = $photo_1;
        $advertisement->photo_2 = $photo_2;
        $advertisement->photo_3 = $photo_3;
        $advertisement->user_id = Auth::user()->id;
        $advertisement->save();

        //redirect to areas with a message of success
        session(['success' => 'Successfully deleted']);

        return redirect('advertisements');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {

        //$ad["photo_1"] ? asset('storage' . $ad["photo_1"]) : null,
        //
        $data = [
            'title'       => $advertisement->title,
            'description' => $advertisement->description,
            'photo_1'     => $advertisement->photo_1 ? asset('storage' . $advertisement->photo_1) : null,
            'photo_2'     => $advertisement->photo_2 ? asset('storage' . $advertisement->photo_2) : null,
            'photo_3'     => $advertisement->photo_3 ? asset('storage' . $advertisement->photo_3) : null,
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
            'photo_1'     => $advertisement->photo_1 ? asset('storage' . $advertisement->photo_1) : null,
            'photo_2'     => $advertisement->photo_2 ? asset('storage' . $advertisement->photo_2) : null,
            'photo_3'     => $advertisement->photo_3 ? asset('storage' . $advertisement->photo_3) : null,
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
        $validated = $request->validate([
            'title'               => ['required', 'string', 'max:225'],
            'description'       => ['required', 'string', 'max:1024'],
        ]);

        $advertisement->title = $request->input('title');
        $advertisement->description = $request->input('description');
        $advertisement->save();
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        $advertisement->visible = "N";
        $advertisement->save();

        return redirect()->back();
    }
}
