
@extends('layouts.dashboard.MessageDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>تفاصيل الرسالة</b></h4></div>
          <div class="card-body">
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
            <div class="col-12 mt-3">
                @if ($users->count() > 0)
                <table class="table table-secondary table-striped table-bordered text-center">
                    <thead>
                        <tr>
                            <th scope='col'>رقم البطل </th>
                            <th scope='col'>اسم البطل</th>
                            <th scope='col'>تم قراءة الرسالة</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $item)
                        <tr class="table-light text-center">
                            <td> {{ $item->pivot->user_id }} </td>
                            <td>
                                {{ $item->first_name.' '.$item->father_name. ' '.$item->last_name }}
                            </td>
                            <td>
                                @if ($item->pivot->read == true)
                                    نعم
                                @else
                                    لا
                                @endif
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
@stop
