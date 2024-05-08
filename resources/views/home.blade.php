@extends('layouts.app')
@section("scss")
    @vite(['resources/sass/home.scss'])
@endsection
@section('content')
    <div style="height: 100vh" class="row">

        <div class="col-2 ps-4 border-end fs-6">
            <div class="d-flex align-items-center">
                <div><img style="width: 45px ;height: 45px" src="{{\Illuminate\Support\Facades\Auth::user()->image}}"
                          alt=""></div>
                <div class="fs-5 fw-bold ms-3">{{\Illuminate\Support\Facades\Auth::user()->name . "'s workspace"}}</div>
            </div>
            <div class="row mt-4 align-items-center">
                <div class="col-1"><i class="fa-solid fa-table fa-lg"></i></div>
                <a class="ps-3 col-10 fs-5" href="{{route('myBoard')}}">My Board</a>
            </div>

            <div class="mt-2 ">
                <div class=" row fs-5 fw-bold">
                    <div class="col-1"><i class="fa-solid fa-list"></i></div>
                    <div class="ps-3 col-10">Joined Boards</div>
                </div>
                <div class="ms-3" id="group-join">
                    @foreach($groupJoined as $groupInfo)
                        <div class="my-1">
                            <i class="fa-solid fa-caret-right"></i>
                            <a class="ms-2"
                               href="{{route("board",$groupInfo->group_id)}}">{{$groupInfo->group->name}}</a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-10">

            <div class="board-control mb-5 border-bottom row">
                <div class="col-8">
                    @if($differentGroup->id == $myGroup->id)
                        <div class="ms-3 pb-2 fs-3">My Board</div>
                    @else
                        <div class="ms-3 pb-2 fs-3">{{$differentGroup->name}}</div>
                    @endif
                </div>
                <div class=" d-flex align-items-center col-2 justify-content-end">
                    @if($differentGroup->user_id == \Illuminate\Support\Facades\Auth::user()->id)

                        <div class="">
                            <i class="fa-solid fa-plus "></i>
                        </div>
                        <div class="ps-2" type="button" data-bs-toggle="modal" data-bs-target="#addNew"
                             data-bs-whatever="">Add members
                        </div>

                    @endif
                </div>
                <div class="col-2 d-flex align-items-center justify-content-center">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                            Board's members
                        </button>
                        <ul style="min-width: 230px" id="dropdown-member" class="dropdown-menu">
                            <li id="member-{{$leader->id}}" class="px-3 pb-1  align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="img-container">
                                        <img class="rounded-5 col-3" style="width: 30px"
                                             src="{{$leader->image}}" alt="">
                                        <div id="display-status-{{$leader->id}}" class="col-1 display-status">
                                        </div>
                                    </div>
                                    <div class="col-8 ms-3">
                                        Owner: {{$leader->name}}
                                    </div>
                                </div>
                            </li>
                            @foreach($userBelongsGroup as $user)
                                <li id="member-{{$user->id}}" class="px-3 pb-1 align-items-center">
                                    <div class="d-flex align-items-center">
                                        <div class="img-container">

                                            <img class="rounded-5 col-3" style="width: 30px"
                                                 src="{{$user->image}}" alt="">
                                            <div id="display-status-{{$user->id}}"
                                                 class="col-1 display-status">

                                            </div>
                                        </div>
                                        <div class="col-8 ms-3">
                                            {{$user->name}}
                                        </div>
                                    </div>

                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="add-task-container">
                <input type="hidden" id="update-at">
                <input type="text" maxlength="12" id="taskText" placeholder="New Task..." onkeydown="if (event.keyCode == 13)
                        document.getElementById('add').click()">
                <button id="add" class="button add-button" onclick="addTask()">Add New Task</button>
            </div>
            <div class="main-container">
                <ul class="columns">

                    <li class="column to-do-column">
                        <div class="column-header">
                            <h4>To Do</h4>
                        </div>
                        <ul style="min-height: 3rem" data-status="1" class="task-list" id="to-do">
                            @foreach($tasks as $task)
                                @if($task->status == 1)
                                    <li data-id="{{$task->id}}" onclick="fillName({{$task->id}})" class="task">
                                        <p data-updated="{{$task->updated_at}}"
                                           id="task-{{$task->id}}">{{$task->name}}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li class="column doing-column">
                        <div class="column-header">
                            <h4>Doing</h4>
                        </div>
                        <ul style="min-height: 3rem" data-status="2" class="task-list" id="doing">
                            @foreach($tasks as $task)
                                @if($task->status == 2)
                                    <li data-id="{{$task->id}}" onclick="fillName({{$task->id}})" class="task">
                                        <p data-updated="{{$task->updated_at}}"
                                           id="task-{{$task->id}}">{{$task->name}}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li class="column done-column">
                        <div class="column-header">
                            <h4>Done</h4>
                        </div>
                        <ul style="min-height: 3rem" data-status="3" class="task-list" id="done">
                            @foreach($tasks as $task)
                                @if($task->status == 3)
                                    <li data-id="{{$task->id}}" onclick="fillName({{$task->id}})" class="task">
                                        <p data-updated="{{$task->updated_at}}"
                                           id="task-{{$task->id}}">{{$task->name}}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>

                    <li class="column trash-column">
                        <div class="column-header">
                            <h4>Trash</h4>
                        </div>
                        <ul style="min-height: 3rem" data-status="4" class="task-list" id="trash">
                            @foreach($tasks as $task)
                                @if($task->status == 4)
                                    <li data-id="{{$task->id}}" onclick="fillName({{$task->id}})" class="task">
                                        <p data-updated="{{$task->updated_at}}"
                                           id="task-{{$task->id}}">{{$task->name}}</p>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        @if($differentGroup->user_id == \Illuminate\Support\Facades\Auth::user()->id)
                            <div class="column-button">
                                <button id="btnDelete" class="button delete-button" onclick="emptyTrash()">Delete
                                </button>
                            </div>
                        @endif
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto"></strong>
                <small>now</small>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">
            </div>
        </div>
    </div>

    <div class="modal fade" id="addNew" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Add Members</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <select name="member" id="member" multiple>
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button id="btnClose" type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button id="btnAddMember" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>

    </div>

@endsection

@section("script")
    <script type="module">
        let btnAddMember = document.querySelector("#btnAddMember")
        let btnClose = document.querySelector("#btnClose")
        let selectElement = document.getElementById('member');
        btnAddMember.addEventListener("click", (e) => {
            // e.preventDefault();
            let selectedOptions = Array.from(selectElement.selectedOptions).map(option => option.value);
            axios.post("{{route("addMember")}}", {
                group_id: "{{$group_id}}",
                user_id: selectedOptions
            })
                .then((res) => {
                    if (res.status == 200) {
                        location.reload();
                        localStorage.setItem('reloadNeeded', 'true');
                    }
                })
        })
        window.addEventListener('load', function () {
            let reloadNeeded = localStorage.getItem('reloadNeeded');
            if (reloadNeeded === 'true') {
                const toastLiveExample = document.getElementById('liveToast');
                const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                document.querySelector(".toast-body").textContent = "Thêm thành công";
                document.querySelector("strong").textContent = "Hệ thống"
                toastBootstrap.show();
                localStorage.removeItem('reloadNeeded');
            }
        });
    </script>
    <script>
        new MultiSelectTag('member')
    </script>
    <script>
        /* Custom Dragula JS */
        let drake = dragula([
            document.getElementById("to-do"),
            document.getElementById("doing"),
            document.getElementById("done"),
            document.getElementById("trash")
        ], {
            removeOnSpill: false
        });

        drake.on("drag", function (element, container) {
            element.className.replace("ex-moved", "");
            // console.log(container)
        })
        drake.on("drop", function (element, container) {
            element.className += "ex-moved";
            axios.post("{{route("taskStatus")}}", {
                task_id: element.dataset.id,
                status: container.dataset.status
            })
        })
        drake.on("over", function (element, container) {
            container.className += "ex-over";
            // console.log(container)
        })
        drake.on("out", function (element, container) {
            container.className.replace("ex-over", "");
            // console.log(container)
        });

        function cancel() {
            let btnCancel = document.getElementById("cancel")
            btnCancel.remove()
            let btnAdd = document.getElementById("add")
            btnAdd.textContent = "Add New Task"
            btnAdd.setAttribute('onclick', "addTask()");
            document.getElementById("taskText").value = ""
        }

        /* Vanilla JS to delete tasks in 'Trash' column */
        function emptyTrash() {
            /* Clear tasks from 'Trash' column */
            let elementTrash = document.querySelectorAll("#trash .task")
            let task_id = Array.from(elementTrash).map((item) =>
                parseInt(item.dataset.id)
            )
            if (confirm("Are you sure delete ?")) {
                axios.post("{{route("deleteTask")}}", {
                    task_id: task_id
                })
                document.getElementById("trash").innerHTML = "";
            }
        }

        function fillName(id) {
            // let taskElements = document.querySelectorAll(".task");
            //
            // for (let i = 0; i < taskElements.length; i++) {
            //     taskElements[i].removeAttribute("onclick");
            // }
            let updateAtElement = document.getElementById("update-at")
            let task = document.getElementById(`task-${id}`)
            updateAtElement.value = task.dataset.updated
            let taskText = document.getElementById("taskText")
            taskText.value = task.textContent
            let btnAdd = document.getElementById("add")
            btnAdd.textContent = "Edit"
            btnAdd.setAttribute('onclick', `editTask(${id})`);
            let addTaskContainer = document.querySelector(".add-task-container");
            addTaskContainer.insertAdjacentHTML('beforeend', '<button onclick="cancel()" id="cancel" class="button cancel-button ms-2">Cancel</button>');

        }

        function addTask() {
            let inputTask = document.getElementById("taskText").value;
            axios.post("{{route("addTask")}}", {
                "task": inputTask,
                "group_id": "{{$group_id}}",
            }).then(res => {
                // console.log(res)
            })
            document.getElementById("taskText").value = "";
        }

        function editTask(id) {
            let task = document.getElementById(`task-${id}`)
            let updateAtInput = document.getElementById("update-at").value
            let inputTask = document.getElementById("taskText").value;
            if (task.textContent != inputTask) {
                axios.post("{{route("editTask")}}", {
                    "id": id,
                    "task": inputTask,
                    "updated_at": updateAtInput
                }).then(res => {
                    if (res.status == 200) {
                        let btnCancel = document.getElementById("cancel")
                        btnCancel.remove()
                        let btnAdd = document.getElementById("add")
                        btnAdd.textContent = "Add New Task"
                        btnAdd.setAttribute('onclick', "addTask()");
                        document.getElementById("taskText").value = ""
                    } else if (res.status == 201) {

                        let btnCancel = document.getElementById("cancel")
                        btnCancel.remove()
                        let btnAdd = document.getElementById("add")
                        btnAdd.textContent = "Add New Task"
                        btnAdd.setAttribute('onclick', "addTask()");
                        document.getElementById("taskText").value = ""
                        const toastLiveExample = document.getElementById('liveToast');
                        const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
                        document.querySelector(".toast-body").textContent = "Task này đã được cập nhật trước đó";
                        document.querySelector("strong").textContent = "Hệ thống"
                        toastBootstrap.show();
                    }
                })
            } else {
                let btnCancel = document.getElementById("cancel")
                btnCancel.remove()
                let btnAdd = document.getElementById("add")
                btnAdd.textContent = "Add New Task"
                btnAdd.setAttribute('onclick', "addTask()");
                document.getElementById("taskText").value = ""
            }


        }
    </script>
    <script type="module">
        function toasts(message, user) {

            const toastLiveExample = document.getElementById('liveToast');
            const toastBootstrap = bootstrap.Toast.getOrCreateInstance(toastLiveExample);
            document.querySelector(".toast-body").textContent = message;
            document.querySelector("strong").textContent = user
            toastBootstrap.show();
        }

        Echo.channel("createTask")
            .listen("CreatedTask", e => {
                toasts(`Đã thêm một task mới`, e.user.name)
                let taskTodo = document.getElementById("to-do")
                let taskToDoChild = `
                 <li data-id="${e.task.id}" onclick="fillName(${e.task.id})" class="task">
                       <p data-updated="${e.task.updated_at}" id="task-${e.task.id}">${e.task.name}</p>
                  </li>
                `
                taskTodo.innerHTML += taskToDoChild;
            })

        Echo.channel("updateTask")
            .listen("UpdatedTask", e => {
                // let taskTodo = document.getElementById("to-do")
                let taskElement = document.getElementById(`task-${e.task.id}`)
                toasts(`Đã cập nhật tên ${taskElement.textContent} thành ${e.task.name}`, e.user.name)
                taskElement.dataset.updated = e.task.updated_at
                taskElement.textContent = e.task.name
            })

        Echo.channel("deleteTask")
            .listen("DeletedTask", e => {
                toasts(`Đã xoá task trong trash`, "Trưởng nhóm")
                let trashElement = document.getElementById("trash")
                trashElement.innerHTML = ""
            })

        Echo.channel("updateStatus")
            .listen("UpdatedStatus", e => {
                toasts(`Đã cập nhật trạng thái ${e.task.name}`, e.user.name)

                let taskElement = document.querySelector(`[data-id="${e.task.id}"]`)
                let boardElement = document.querySelector(`[data-status="${e.task.status}"]`)
                taskElement.remove()
                boardElement.innerHTML += `
                 <li data-id="${e.task.id}" onclick="fillName(${e.task.id})" class="task">
                     <p data-updated="${e.task.updated_at}" id="task-${e.task.id}">${e.task.name}</p>
                 </li>
                `
            })
        let ulElement = document.querySelector("#dropdown-member")
        Echo.join('joinBoard')
            .here((users) => {
                users.forEach((item) => {
                    let displayStatusElement = document.getElementById(`display-status-${item.id}`)
                    if (displayStatusElement) {
                        displayStatusElement.innerHTML = `<div class=" status"></div>`
                    }

                })
            })
            .joining((user) => {
                // toasts(`${user.name} đang online`, "Hệ thống")
                let displayStatusElement = document.getElementById(`display-status-${user.id}`)
                if (displayStatusElement) {
                    displayStatusElement.innerHTML = `<div class=" status"></div>`
                }
            })
            .leaving((user) => {
                // toasts(`${user.name} đã offline`, "Hệ thống")
                let displayStatusElement = document.getElementById(`display-status-${user.id}`)
                if (displayStatusElement && displayStatusElement.querySelector(".status")) {
                    displayStatusElement.removeChild(displayStatusElement.querySelector(".status"))
                }
            })
        Echo.private("displayGroup.{{\Illuminate\Support\Facades\Auth::user()->id}}")
            .listen("DisplayGroup", (e) => {
                toasts(`Đã thêm bạn vào nhóm ${e.group.name}`, e.user.name)
                let elementGroupJoin = document.getElementById("group-join")
                elementGroupJoin.innerHTML = `

                <div class="my-1">
                    <i class="fa-solid fa-caret-right"></i>
                    <a class="ms-2" href="/board/${e.group.id}">${e.group.name}</a>
                </div>
                `
            })

    </script>
@endsection
