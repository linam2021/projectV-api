@extends('layouts.dashboard.pathDashboard')
@section('dashboard-content')
<div class="container">
    <div class="row">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                  <p>{{\Session::get('success')}}</p>
            </div>
            @endif
    </div>
    <div class="row">
        @if (\Session::has('error'))
        <div class="alert alert-danger">
            <p>{{\Session::get('error')}}</p>
        </div>
      @endif
    </div>
    <div class="row">
        <div class="col-3 d-flex justify-content-between align-items-center">
            <h2 class="text-primary">الرسائل</h2>
            <div class="float-left">

            </div>
            <a href="{{route('messages.create')}}" class="btn btn-primary pull-right ">ارسال رسالة</a>
        </div>
        <div class="col-12 mt-3">
            @if ($messages->count() > 0)
            <table class="table table-bordered">
                <thead >
                    <tr>
                        <th scope='col'>رقم الرسالة</th>
                        <th scope='col'>العنوان</th>
                        <th scope='col'>الموضوع</th>
                        <th scope='col'>اسم الادمين المرسل</th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($messages as $item)
                    <tr>
                        <td>{{$item->id}}</td>
                        <td>{{$item->title}}</td>
                        <td>{{$item->body}}</td>
                        <td>
                            @foreach ($admins as $admin )
                          @if ($item->admin_id == $admin->id)
                            {{ $admin->last_name}}
                        @endif

                       @endforeach
                        </td>
                        <td>
                            <div class="row">
                                <div class="col">
                                    <a  title="اظهار معلومات الرسالة " class="text-dark" href="{{ route('message.showMessage',['id' => $item->id]) }}"> <i class="fas fa-eye"> </i></a>
                                  </div>
                            </div>
                        </td>
                        <td>
                            <a href="{{route('message.destroy',['id' => $item->id] )}}" class="btn btn-danger">حذف</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-warning" role="alert">
            لا توجد اية رسالة وقت الحالي.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
