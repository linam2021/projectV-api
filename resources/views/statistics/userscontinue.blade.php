@extends('layouts.dashboard.pathDashboard')
@php
    $i = 1;
@endphp
@section('dashboard-content')
<div class="row">
    <h2 class="col-12 text-primary">المستخدمين المستمرين في مسار {{$path_name}}</h2>
    <p class="col-12 text-info mt-2 mb-2 font-weight-bold h5">العدد الاجمالي: {{$usersPath->count()}}</p>
    <div class="col-12">
        @if ($usersPath->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">رقم تسلسلي</th>
                    <th scope="col">اسم البطل</th>
                    <th scope="col">هاتف</th>
                    <th scope="col">تلغرام</th>
                    <th scope="col">البلد</th>
                    <th scope="col">عدد النقاط</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usersPath as $item)
                <tr>
                    <th scope="row">{{$i++}}</th>
                    <th>{{$item->user_id}}</th>
                    <th>{{$item->user->first_name .' ' . $item->user->father_name .' ' . $item->user->last_name }}</th>
                    <th>{{$item->user->phone}}</th>
                    <th>{{$item->user->telegram}}</th>
                    <th>{{$item->user->country}}</th>
                    <th>{{$item->score}}</th>
                </tr>
                @endforeach
            </tbody>
        </table>
        {!! $usersPath->onEachSide(10)->links() !!}
        @else
        <div class="alert alert-warning" role="alert">
        لا يوجد اي مستخدم في الوقت الحالي
        </div>
        @endif
    </div>
</div>
@endsection
