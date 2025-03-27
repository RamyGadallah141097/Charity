<div class="modal-body">
    <form id="addForm" class="addForm" method="POST" enctype="multipart/form-data" action="{{route('tasks.update' , $task->id)}}">
        @csrf
        @method("put")

        <div class="form-group">
            <label for="title" class="form-control-label" > العنوان</label>
            <input type="text" value="{{$task->title}}"  class="form-control" name="title"   id="title">
        </div>
        <div class="form-group">
            <label for="description" class="form-control-label" > الوصف</label>
            <textarea type="text"   class="form-control" name="description"   id="description"> {{$task->description}} </textarea>
        </div>

            <input type="hidden"   value="{{ $task->from_date }}"  class="form-control" name="from_date"   id="from_date">

        <div class="form-group">
            <label for="to_date" class="form-control-label" > وقت الانتهاء</label>
            <input type="date"   value="{{ $task->to_date }}"  class="form-control" name="to_date"   id="to_date">
        </div>



                <div class="form-group">
                    <label for="status" class="form-control-label"> تعديل تقدم الفكره ؟ </label>
                    <select name="status" id="type" class="form-control">
                        <option value="{{$task->status}}" selected>
                            {{$task->status == 0 ? '0%' :
                                ($task->status == 1 ? '25%' :
                                 ($task->status == 2 ? '50%' :
                                  ($task->status == 3 ? '75%' : '100%'))) }}
                        </option>  // selected
                        <option value="0"> 0%  </option>  // the first type 0
                        <option value=1"> 25%  </option>  // the first type 1
                        <option value="2"> 50%</option> // the second type 2
                        <option value="3"> 75% </option>// the third type 3
                        <option value="4">  100% </option>// the forth type 4
                    </select>
                </div>




        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">اغلاق</button>
            <button type="submit" class="btn btn-primary" id="addButton">تحديث</button>
        </div>
    </form>
</div>
