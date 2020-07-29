<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjaxController extends Controller
{
    public function getGenres ()
    {
        if ($_POST['parent_id'] > 0) {
            $parentId = intval($_POST['parent_id']);
            $data = DB::table('terms')->where('parent_id', $parentId)->get();
            header('Content-Type: application/json');
            echo json_encode($data);
        }
    }
}
