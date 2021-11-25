@extends('layouts.dashboard.pathDashboard')
@php
    $i = 1;
@endphp
@section('dashboard-content')
<div class="row">
    <h2 class="col-12 text-primary">الإجابة المستخدمين في مسار {{$path_name}}</h2>
    <p class="col-12 text-info mt-2 mb-2 font-weight-bold h5">العدد الاجمالي: {{$usersPath->count()}}</p>
    <div class="col-12">
        @if ($usersPath->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">رقم تسلسلي</th>
                    <th scope="col">اسم البطل</th>
                    <th scope="col">الاجابة الاولى</th>
                    <th scope="col">الاجابة الثانية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usersPath as $item)
                <tr>
                    <th scope="row">{{$i++}}</th>
                    <th>{{$item->user_id}}</th>
                    <th>{{$item->user->first_name .' ' . $item->user->father_name .' ' . $item->user->last_name }}</th>
                    <th>{{$item->answer_join}}</th>
                    <th>{{$item->answer_accept_order}}</th>
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
