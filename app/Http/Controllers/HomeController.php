<?php

namespace App\Http\Controllers;

use App\Events\CreatedTask;
use App\Events\DeletedMember;
use App\Events\DeletedTask;
use App\Events\DisplayGroup;
use App\Events\JoinBoard;
use App\Events\UpdatedStatus;
use App\Events\UpdatedTask;
use App\Events\WorkingTeam;
use App\Models\Group;
use App\Models\Group_user;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index($group_id)
    {
        $userBelongsGroup = User::query()->with("group_users")
            ->whereHas('group_users', function ($query) use ($group_id) {
                $query->where('group_id', $group_id);
            })
            ->select('name', 'id', 'image')
            ->get();
        $userIds = $userBelongsGroup->pluck('id');
        $users = User::query()->where("id", "!=", Auth::user()->id)->whereNotIn("id", $userIds)->get();
        $myGroup = Group::query()->where("user_id", Auth::user()->id)->select("id")->first();
        $differentGroup = Group::query()->where("id", $group_id)->select(["name", "id", "user_id"])->first();
        $groupJoined = Group_user::query()->with("Group")->where("user_id", Auth::user()->id)->get();
        $tasks = Task::query()->where("group_id", "=", $group_id)->get();
        $leader = User::query()->with("group")
            ->whereHas('group', function ($query) use ($group_id) {
                $query->where('id', $group_id);
            })->select('name', 'id', 'image')->first();
        return view('home', compact(['users', 'group_id', 'groupJoined', 'tasks', 'myGroup', 'differentGroup', "userBelongsGroup", "leader"]));
    }

    public function myBoard()
    {

        $group = Group::query()->where("user_id", Auth::user()->id)->first();
        $group_id = $group->id;
        $userBelongsGroup = User::query()->with("group_users")
            ->whereHas('group_users', function ($query) use ($group_id) {
                $query->where('group_id', $group_id);
            })
            ->select('name', 'id', 'image')
            ->get();
        $userIds = $userBelongsGroup->pluck('id');
        $users = User::query()->where("id", "!=", Auth::user()->id)->whereNotIn("id", $userIds)->get();
        $myGroup = Group::query()->where("user_id", Auth::user()->id)->select("id")->first();
        $differentGroup = Group::query()->where("id", $group_id)->select(["name", "id", "user_id"])->first();
        $groupJoined = Group_user::query()->with("Group")->where("user_id", Auth::user()->id)->get();
        $tasks = Task::query()->where("group_id", "=", $group_id)->get();
        $leader = User::query()->with("group")
            ->whereHas('group', function ($query) use ($group_id) {
                $query->where('id', $group_id);
            })->select('name', 'id', 'image')->first();
        return view('home', compact(['users', 'group_id', 'groupJoined', 'tasks', 'myGroup', 'differentGroup', "userBelongsGroup", "leader"]));
    }


    public function addMember(Request $request)
    {

        if ($request->user_id) {
            foreach ($request->user_id as $user_id) {
                $group_user = Group_user::query()->create([
                    "group_id" => $request->group_id,
                    "user_id" => $user_id
                ]);
                $group = Group::query()->where("id", $request->group_id)->first();
                broadcast(new DisplayGroup($group, $user_id));

            }

        }

        return response()->json([
            "group_id" => $request->group_id,
            "user_id" => $request->user_id,
            "group_user" => $group_user
        ]);
    }

    public function addTask(Request $request)
    {

        $task = Task::query()->create([
            "name" => $request->task,
            "status" => 1,
            "group_id" => $request->group_id,
        ]);
        broadcast(new CreatedTask($task));
        return response()->json([
            "task" => $request->task,
            "group_id" => $request->group_id,
        ]);
    }

    public function deleteTask(Request $request)
    {
        $task = Task::query()->whereIn("id", $request->task_id)->get();
        broadcast(new DeletedTask($task));
        Task::query()->whereIn("id", $request->task_id)->delete();
        return response()->json([
            "task_id" => $request->task_id,
        ]);
    }

    public function editTask(Request $request)
    {
        $taskOld = Task::query()->where("id", $request->id)->first();
        $updated_at = (new \DateTime($taskOld->updated_at));
        if( $updated_at > (new \DateTime($request->updated_at))){
            return response()->json(['date' => $updated_at], 201);
        }else{
            Task::query()->where("id", $request->id)->update([
                "name" => $request->task,
                "status" => $taskOld->status,
            ]);
            $taskNew = Task::query()->where("id", $request->id)->first();
            broadcast(new UpdatedTask($taskNew));
            return response()->json([
                "id" => $request->id,
                "task" => $request->task,
                "updated_at" => $request->updated_at
            ]);
        }

    }


    public function taskStatus(Request $request)
    {
        Task::query()->where("id", $request->task_id)->update([
            "status" => (int)$request->status,
        ]);
        $task = Task::query()->where("id", $request->task_id)->first();
        broadcast(new UpdatedStatus($task));
        return response()->json([
            "task_id" => $request->task_id,
            "status" => $request->status,
        ]);
    }


    public function deleteMember(Request $request){
        Group_user::query()->where("user_id",$request->user_id)->where("group_id",$request->group_id)->delete();
        broadcast(new DeletedMember($request->user_id, $request->group_id));
        return response()->json([
            "user_id" => $request->user_id,
            "group_id" => $request->group_id,
        ]);
    }
}
