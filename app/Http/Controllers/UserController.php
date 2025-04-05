<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;


class UserController extends Controller
{
   public function index(Request $request)
   {
       $search = $request->input('search', '');
       $filters = $request->only(['email', 'name']);
       $perPage = $request->input('per_page', 4); // Default to 4 if not provided

       $query = User::query();

       if ($search) {
           $query->where(function ($query) use ($search) {
               $query->where('name', 'like', '%' . $search . '%')
                     ->orWhere('email', 'like', '%' . $search . '%');
           });
       }

       foreach ($filters as $key => $value) {
           if ($value) {
               $query->where($key, 'like', '%' . $value . '%');
           }
       }

       // Paginate with dynamic perPage value
       $data = $query->orderBy('created_at', 'asc')->paginate($perPage);

       return response()->json([
           'data' => $data->items(),
           'pagination' => [
               'current_page' => $data->currentPage(),
               'last_page' => $data->lastPage(),
               'per_page' => $data->perPage(),
               'total' => $data->total(),
               'next_page_url' => $data->nextPageUrl(),
               'prev_page_url' => $data->previousPageUrl(),
           ]
       ]);
   }


      public function show(){

        return view('index');

      }
}
