@extends('layouts.dashboard.pathDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>إضافة مسار</b></h4></div>
          <div class="card-body">
            <form action=" {{ route('message.store') }} " method="POST" enctype="multipart/form-data">
                @csrf
                @if (\Session::has('success'))
                <div class="alert alert-success">
                    <p>{{\Session::get('success')}}</p>
                </div>
                @endif
                @if (\Session::has('error'))
                <div class="alert alert-danger">
                    <p>{{\Session::get('error')}}</p>
                </div>
                @endif
                <div class="row align-items-center">
                    <label for="pathname" class="form-label">اسم المسار</label>
                    <div class="col-md-12 mr-2">
                        <select class="form-select" name ="path_name" aria-label="Default select example" >
                            @foreach($paths as $item)
                            <option value="{{ $item->id }}"> {{ $item->path_name }} </option>
                          @endforeach
                          </select>
                    </div>
                    <div class="col-md-12 mr-2">
                        <label for="titel" class="form-label">العنوان</label>
                        <input class="form-control" type="text" name="title" id="title">
                    </div>
                    <div class="col-md-12 mr-2">
                        <label for="body" class="form-label">موضوع الرسالة</label>
                        <textarea name="body" id="" cols="100" rows="10"></textarea>
                    </div>
                </div>
                <div class="col-md-12 mr-2">
                    <label for="sendNotification" class="form-label">تريد ارسال اشعار </label>
                    <input style=" width:20px ; height:20px"
                          class="form-check-input" type="checkbox" value="true"  name="sendNotification" id="sendNotification" >
                </div>

                <div class="col-md-12 text-center my-3">
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
@stop
