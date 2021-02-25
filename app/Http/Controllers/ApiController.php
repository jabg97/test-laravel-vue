<?php

namespace App\Http\Controllers;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{

    public function __construct()
    {
       
    }

    public function get_menu()
    {
        $data = Menu::select(['id', 'label AS name', 'url', 'icon' ,'parent_id'])
        ->with(['children' => function($query){
            $query->select(['id', 'label AS name', 'url', 'icon' ,'parent_id']);
        }])->whereNull('parent_id')->get();
        return response()->json($data);
    }

    public function user_joins()
    {
        $data = DB::table('likes', 'source')->select('source.id','user1.name AS user1_name', 'user2.name AS user2_name')
        ->join('likes AS target', function($join)
        {
            $join->on('source.user_id', '=', 'target.user_liked_id')
            ->on('target.user_id', '=', 'source.user_liked_id')
            ->on('source.user_id', '<', 'target.user_id');
        })
        ->join('users AS user1', 'source.user_id', '=', 'user1.id')
        ->join('users AS user2', 'source.user_liked_id', '=', 'user2.id')
        
        ->get();
        return response()->json($data);
    }

}
