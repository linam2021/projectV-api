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

        </div>
        <div class="col-12 mt-3">
            @if ($users->count() > 0)
            <table class="table table-bordered">
                <thead >
                    <tr>
                        <th scope='col'>رقم الطالب </th>
                        <th scope='col'>تم قراءة الرسالة</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $item)
                    <tr>
                        <td> {{ $item->user_id }} </td>
                        <td> @if ($item->read == true)
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
            لا توجد اية رسالة وقت الحالي.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
