<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

use App\Models\Photo;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return string
     */
    public function index ()
    {
        $photos = Photo::all ();

        $retData = json_encode([
            "success" => true,
            "photos"  => $photos
        ]);

        return ($retData);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return string
     */
    public function store (Request $request)
    {
        $newPath = ($request -> all ())['path'];
        
        $newPhoto         = new Photo;
        $newPhoto['path'] = $newPath;
        $newPhoto -> save ();

        $retData = json_encode([
            "success" => true,
            "photo"   => $newPhoto
        ]);

        return ($retData);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return string
     */
    public function show ($id)
    {
        $photo = Photo::find ($id);
        if ($photo == null)
            return (json_encode([
                "success" => false,
                "message" => "Photo does not exist"
            ]));

        $retData = json_encode([
            "success" => true,
            "photo"   => $photo
        ]);

        return ($retData);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return string
     */
    // Unused at the moment
    public function update (Request $request, $id)
    {
        $photo   = Photo::find ($id);
        $newPath = ($request -> all ())['path'];

        if ($photo == null)
            return (json_encode([
                "success" => false,
                "message" => "Photo does not exist"
            ]));

        $photo['path'] = $newPath;
        $photo -> save ();

        $retData = json_encode([
            "success" => true,
            "photo"  => $photo
        ]);

        return ($retData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return string
     */
    public function destroy ($id)
    {
        $photo = Photo::find ($id);

        if ($photo == null)
            return (json_encode([
                "success" => false,
                "message" => "Photo does not exist"
            ]));

        $photo -> delete ();

        $retData = json_encode([
            "success" => true,
            "message" => "None"
        ]);

        return ($retData);
    }
}
