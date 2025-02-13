<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;

class PropertyController extends Controller
{

    public function index()
    {
        // Fetch all properties from the database
        $properties = Property::paginate(10);

        // Pass properties to the view
        return view('manage-property.index', compact('properties'));
    }

}
