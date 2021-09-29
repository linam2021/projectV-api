@extends('layouts.dashboard.pathDashboard')

@php
    $i = 1;
@endphp

@section('dashboard-content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-primary">{{$name}}</h2>
        </div>
        <div class="col-12 mt-3">
            @if ($usersPath->count() > 0)
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>id</th>
                        <th scope='col'>إسم الأول</th>
                        <th scope='col'>إسم العائلة</th>
                        <th scope='col'>إسم الأخير</th>
                        <th scope='col'>تلغرام</th>
                        <th scope='col'>رقم الهاتف</th>
                        <th scope='col'>البلد</th>
                        <th scope='col'>الجنس</th>
                        <th scope='col'>نقاط</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usersPath as $item)
                    <tr>
                        <th scope="row">{{$i++}}</th>
                        <th scope="row">{{$item->user_id}}</th>
                        <td>{{$item->user->first_name}}</td>
                        <td>{{$item->user->father_name}}</td>
                        <td>{{$item->user->last_name}}</td>
                        <td>{{$item->user->telegram}}</td>
                        <td>{{$item->user->phone}}</td>
                        <td>{{$item->user->country}}</td>
                        <td>{{$item->user->gender}}</td>
                        <td>{{$item->score}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {!! $usersPath->onEachSide(10)->links() !!}
            @else
            <div class="alert alert-warning" role="alert">
            ليس لديك أي مستخدم في وقت الحالي.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
