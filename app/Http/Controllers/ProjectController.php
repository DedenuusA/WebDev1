<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Project;
use Yajra\DataTables\Facades\DataTables;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $projects = Project::query();
        
    if ($request->has('sumber_dana')) {
        $projects->where('sumber_dana', 'like', '%' . $request->sumber_dana . '%');
    }

    
    if ($request->has('keterangan')) {
        $projects->where('keterangan', 'like', '%' . $request->keterangan . '%');
    }
        return DataTables::of($projects)
            ->addColumn('action', function ($project) {
                 
                $showBtn =  '<button ' .
                                ' class="btn btn-outline-info" ' .
                                ' onclick="showProject(' . $project->id . ')">Show' .
                            '</button> ';
 
                $editBtn =  '<button ' .
                                ' class="btn btn-outline-success" ' .
                                ' onclick="editProject(' . $project->id . ')">Edit' .
                            '</button> ';
 
                $deleteBtn =  '<button ' .
                                ' class="btn btn-outline-danger" ' .
                                ' onclick="destroyProject(' . $project->id . ')">Delete' .
                            '</button> ';
 
                return $showBtn . $editBtn . $deleteBtn;
            })
            ->rawColumns(
            [
                'action',
            ])
            ->make(true);
    }
 
    public function store(Request $request)
    {
        request()->validate([
            'sumber_dana' => 'required',
            'program' => 'required',
            'keterangan' => 'required',
        ]);
  
        $project = new Project();
        $project->sumber_dana = $request->sumber_dana;
        $project->program = $request->program;
        $project->keterangan = $request->keterangan;
        $project->save();
        return response()->json(['status' => "success"]);
    }
 
    public function show($id)
    {
        $project = Project::find($id);
        return response()->json(['project' => $project]);
    }
 
    public function update(Request $request, $id)
    {
        request()->validate([
            'sumber_dana' => 'required',
            'program' => 'required',
            'keterangan' => 'required'
        ]);
  
        $project = Project::find($id);
        $project->sumber_dana = $request->sumber_dana;
        $project->program = $request->program;
        $project->keterangan = $request->keterangan;
        $project->save();
        return response()->json(['status' => "success"]);
    }
 
    public function destroy($id)
    {
        Project::destroy($id);
        return response()->json(['status' => "success"]);
    }
}