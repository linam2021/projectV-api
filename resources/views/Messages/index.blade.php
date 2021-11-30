@extends('layouts.dashboard.MessageDashboard')
@section('dashboard-content')
<div class="row justify-content-center">
  <div>
    <div class="card border-left-success shadow h-100">
      <div class="card-header text-primary"><h4><b> عرض الرسائل </b></h4></div>
        <div class="card-body">
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
            <div class="table-responsive">
                @if ($messages->count() > 0)
                <table class="table table-secondary table-striped table-bordered text-center">
                    <thead >
                        <tr>
                            <th scope='col'>الرقم</th>
                            <th scope='col'>عنوان الرسالة</th>
                            <th scope='col'>الموضوع</th>
                            <th scope='col' class="text-nowrap">اسم المرسل</th>
                            <th>تفاصيل </th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($messages as $item)
                        <tr class="table-light text-center">
                            <td>{{$item->id}}</td>
                            <td>{{$item->title}}</td>
                            <td>{{$item->body}}</td>
                            <td>
                                {{ $item->user->first_name.' '.$item->user->father_name. ' '.$item->user->last_name }}
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col">
                                        <a  title="اظهار معلومات الرسالة " class="text-dark" href="{{ route('message.showMessage',['id' => $item->id]) }}"> <i class="fas fa-eye"> </i></a>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="col">
                                    <a title="حذف رسالة" class="text-danger" href="{{route('message.destroy',['id' => $item->id] )}}" > <i class="fas fa-trash-alt"></i></a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="alert alert-warning" role="alert">
                    لا يوجد رسائل  في الوقت الحالي.
                </div>
                @endif
            </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
