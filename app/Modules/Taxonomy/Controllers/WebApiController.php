<?php

namespace App\Modules\Taxonomy\Controllers;

use App\Http\Controllers\Controller;
use Taxonomy;

class WebApiController extends Controller
{
    public function filterVocabularies()
    {
        $result = Taxonomy::apiFilterVocabularies($this->request);

        return response()->json($result);
    }

    public function getCategories()
    {
        $result = Taxonomy::apiGetCategories($this->request);

        return response()->json($result->data, $result->statusCode);
    }

    public function getLanguages()
    {
        $result = Taxonomy::apiGetLanguages($this->request);

        return response()->json($result->data, $result->statusCode);
    }

    public function getSubCategories()
    {
        $result = Taxonomy::apiGetSubCategories($this->request);

        return response()->json($result->data, $result->statusCode);
    }

    public function getTermData()
    {
        $result = Taxonomy::apiGetTermData($this->request->termId);

        return response()->json($result->data, $result->statusCode);
    }

    public function getTopCategories()
    {
        $result = Taxonomy::apiGetTopCategories();

        return response()->json($result->data, $result->statusCode);
    }

    public function rebuildTree()
    {
        $result = Taxonomy::rebuildTree($this->request);

        return response()->json($result->data, $result->statusCode);
    }
}