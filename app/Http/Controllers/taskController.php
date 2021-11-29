<?php

namespace App\Http\Controllers;

use App\Models\task;
use Illuminate\Http\Request;

class taskController extends Controller
{
    //request and get list_id
    public function task(Request $request)
    {
        $task = task::where('list_id', request()->list_id)->get();

        return view('task', compact('task'));
    }

    //save task data by list_id
    public function store(Request $request, $id)
    {

        $list_id = explode('/', $request->url());

        $data = $request->validate([
            'content' => 'required'
        ]);

        $savedata = [
            'content' => $request->content,
            'list_id' => (int)$list_id[4]
        ];

        task::create($savedata);

        return back();
    }

    //view task
    public function index()
    {
        $todolists = task::all();
        return view('dashboard', compact('task'));
    }

    public function destroy(Request $request)
    {
        $list_id = explode('/', $request->url());
        $taskdel = task::findOrFail((int)$list_id[4]);
        $taskdel->delete();
        return back();
    }
    
     //view edit
     public function taskedit(Request $request)
     {
         $list_id = explode('/', $request->url());
         $task = task::where('id', (int)$list_id[4])->get();
         //dd($todolists[0]);
         return view('edittask', compact('task'));  
     }
 
     //edit list
     public function edit(Request $request)
     {
         $data = $request->validate([
             'content' => 'required'
         ]);
         $list_id = explode('/', $request->url());
         $taskedit = task::findOrFail((int)$list_id[4]);
         //$listTask = Todolists::where('id', (int)$list_id[4]);
         $taskedit->content = $data['content'];
         //dd($data);
 
         $taskedit->save();
         return redirect('dashboard');
     }
    public function logout()
    {
        return redirect('welcome');
    }
}
