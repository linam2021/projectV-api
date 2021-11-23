@extends('layouts.dashboard.pathDashboard')
@section('dashboard-content')
@php
    $i = 1;
@endphp
<div class="row justify-content-center">
  <div>
    <div class="card border-left-success shadow h-100">
      @if($courses->count()>0)
      @endif
      <div class="card-header text-primary"><h4><b>   المراحل الخاصة بالمسار &nbsp;{{$path->path_name}} </b></h4></div>
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
            <div class="table-responsive"> 
              @if($courses->count()>0)
              <table class="table table-secondary table-striped table-bordered">
                  <thead>
                      <tr class="text-center">
                          <th scope="col">الرقم</th>
                          <th scope="col">اسم المرحلة </th>
                          <th scope="col">رابط المرحلة </th>
                          <th scope="col">المدة (يوم)</th>
                          <th scope="col">ترتيب المرحلة</th>
                      </tr>
                  </thead> 
                  <tbody>
                      @foreach ($courses as $course)
                          <tr class="table-light text-center">
                            <td scope="row">{{$i++}}</td>
                            <td scope="row">{{$course->course_name}}</td>
                            <td scope="row">{{$course->course_link}}</td>
                            <td scope="row">{{$course->course_duration}}</td>
                            <td scope="row">{{$course->stage}}</td>
                          </tr>           
                      @endforeach
                  </tbody>
              </table>
              </div>
              @endif
            </div>
        </div>
  </div>
</div>
@stop