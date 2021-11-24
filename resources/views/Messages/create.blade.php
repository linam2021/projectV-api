@extends('layouts.dashboard.MessageDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>إرسال رسالة</b></h4></div>
          <div class="card-body">
            <form action=" {{ route('message.store') }} " method="POST">
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
                        <label for="titel" class="form-label">عنوان الرسالة</label>
                        <input class="form-control" type="text" name="title" id="title">
                    </div>
                    <div class="col-md-12 mr-2">
                        <label for="body" class="form-label">موضوع الرسالة</label>
                        <div >
                            <textarea  class="form-control" rows="10" name="body" ></textarea>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mr-2">
                    <input style=" width:20px ; height:20px"
                          class="form-check-input" type="checkbox" value="true"  name="sendNotification" id="sendNotification" >
                    <label for="sendNotification" class="form-label" > ارسال اشعار </label>      
                </div>

                <div class="col-md-12 text-center my-3">
                    <button type="submit" class="btn btn-primary">إرسال</button>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
@stop
