<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PeopleCollection;
use App\Models\People;
use App\Http\Resources\PeopleResource;

class PeopleController extends Controller
{
    public function index()
    {
        return new PeopleCollection(People::paginate(10));
    }

    public function show($id)
    {
        $people = People::find($id);
        return new PeopleResource($people);
    }
}
