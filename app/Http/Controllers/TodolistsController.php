<?php

namespace App\Http\Controllers;

use App\Models\task;
use App\Models\Todolists;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TodolistsController extends Controller
{
    // display list
    public function index()
    {
        // $todolists = Todolists::all();
        $todolists = Todolists::where('user_id', Auth::id())->get();

        return view('dashboard', compact('todolists'));
    }

    // create list
    public function store(Request $request)
    {
        $data = $request->validate([
            'content' => 'required'
        ]);

        Todolists::create([
            'content' => $request->input('content'),
            'user_id' => Auth::id()
        ]);
        return back();
    }

    // delete list
    public function destroy(Request $request)
    {
        $list_id = explode('/', $request->url());
        $listdel = Todolists::findOrFail((int)$list_id[4]);
        $listTask = task::where('list_id', (int)$list_id[4])->get();

        if(!$listTask->isEmpty()) {
            foreach($listTask as $l) {
                $l->delete();
            }
        }

        $listdel->delete();
        return back();
    }

    //view edit
    public function listedit(Request $request)
    {
        $list_id = explode('/', $request->url());
        $todolists = Todolists::where('id', (int)$list_id[4])->get();
        //dd($todolists[0]);
        return view('editlist', compact('todolists'));  
    }

    //edit list
    public function edit(Request $request)
    {
        $data = $request->validate([
            'content' => 'required'
        ]);
        $list_id = explode('/', $request->url());
        $listedit = Todolists::findOrFail((int)$list_id[4]);
        //$listTask = Todolists::where('id', (int)$list_id[4]);
        $listedit->content = $data['content'];
        //dd($data);

        $listedit->save();
        return redirect('dashboard');
    }
    
    public function logout()
    {
        return redirect('welcome');
    }
}
