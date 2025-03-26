<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\TaskRequest;
use App\Models\Donation;
use App\Models\Task;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tasks = Task::all();

            return Datatables::of($tasks)
                ->addColumn('status', function ($task) {
                    switch ($task->status) {
                        case 0:
                            return "
                                            <div class='progress'>
                                              <div class='progress-bar ' role='progressbar' style='width: 0% ; color:black' aria-valuenow='0' aria-valuemax='100'>0%</div>
                                            </div>
                                    ";
                            break;
                        case 1:
                            return "
                                        <div class='progress'>
                                          <div class='progress-bar' role='progressbar' style='width: 25% ; color:black' aria-valuenow='25' aria-valuemax='100'>25%</div>
                                        </div>
                                ";
                            break;
                        case 2:
                            return "
                                        <div class='progress'>
                                          <div class='progress-bar' role='progressbar' style='width: 50% ; color:black' aria-valuenow='50' aria-valuemax='100'>50%</div>
                                        </div>
                                ";
                            break;
                        case 3:
                            return "
                                        <div class='progress'>
                                          <div class='progress-bar' role='progressbar' style='width: 75% ; color:black' aria-valuenow='75' aria-valuemax='100'>75%</div>
                                        </div>
                                ";
                            break;
                        case 4:
                            return "
                                        <div class='progress'>
                                          <div class='progress-bar' role='progressbar' style='width: 100% ; color:black' aria-valuenow='100' aria-valuemax='100'>100%</div>
                                        </div>
                                ";
                            break;
                        default:
                            return 'تبرع عيني';
                    }
                })
                ->editColumn('description', function ($task) {
                    return '<span class="small-text-hover">' . ($task->description ? $task->description : '-----') . '</span>';
                })
                ->addColumn('action', function ($task) {
                    $editButton = '';
                    $deleteButton = '';

                    // التحقق من إذن التعديل
                        $editButton = '
                            <button type="button" data-id="' . $task->id . '" class="btn btn-pill btn-info-light editBtn">
                                <i class="fa fa-edit"></i>
                            </button>
                        ';

                    // التحقق من إذن الحذف
                        $deleteButton = '
                            <button class="btn btn-pill btn-danger-light" data-toggle="modal" data-target="#delete_modal"
                                    data-id="' . $task->id . '">
                                <i class="fas fa-trash"></i>
                            </button>
                        ';

                    return '<div class="d-flex">' . $editButton . $deleteButton . '</div>';
                })

                ->escapeColumns([])
                ->make(true);
        } else {
            return view('Admin/task/index');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("Admin/task/parts/create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {

        if(Task::create($request->except('_token'))){
            return response()->json([
                "status" => 200,
                "message" => "تم الحفظ بنجاح"
            ]);
        }else{
            return response()->json([
                "status" => 500,
                "message" => "حدث خطأ ما"
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function edit(Task $task)
    {
        return view("Admin/task/parts/edit", ["task" => $task]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        if ($task->update($request->except('_token', '_method'))) {
            return response()->json([
                "status" => 200,
                "message" => "تم الحفظ بنجاح"
            ]);
        } else {
            return response()->json([
                "status" => 500,
                "message" => "حدث خطأ ما"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {

    }

    public function delete(Request $request)
    {
        try {
            Task::destroy($request->id);
            return redirect()->back();
//            return response(['message' => 'تم الحذف بنجاح', 'status' => 200], 200);
        } catch (\Exception $ex) {
            return response(['message' => $ex->getMessage(), 'status' => 400]);
        }

    }
}
