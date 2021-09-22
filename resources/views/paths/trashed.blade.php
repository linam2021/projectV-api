@extends('layout.main')

@php
    $i = 1;
@endphp

@section('title', 'المسارات')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-primary">المسارات المحذوفة</h2>
        </div>
        <div class="col-12 mt-3">
            @if ($paths->count() > 0)
            <table class="table table-dark table-striped">
                <thead>
                    <tr>
                        <th scope='col'>#</th>
                        <th scope='col'>رقم بنك</th>
                        <th scope='col'>إسم المسار</th>
                        <th scope='col'>جميع المستخدمين</th>
                        <th scope='col'>جميع المستخدمين المقصيين</th>
                        <th scope="col">حدث</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paths as $item)
                    <tr>
                        <th scope="row">{{$i++}}</th>
                        <th scope="row">{{$item->questionbank_path_id}}</th>
                        <td>{{$item->path_name}}</td>
                        <td>
                            <a href="{{route('paths.users', $item->id)}}" class="text-warning"><span data-feather="external-link"></span></a>
                        </td>
                        <td>
                            <a href="{{route('paths.excludeusers', $item->id)}}" class="text-warning"><span data-feather="external-link"></span></a>
                        </td>
                        <td>
                            <a href="{{route('paths.restore', $item->id)}}" class="btn btn-primary">استعادة</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="alert alert-warning" role="alert">
            ليس لديك أي مسار في وقت الحالي.
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
