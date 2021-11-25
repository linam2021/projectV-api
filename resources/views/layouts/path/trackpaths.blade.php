@extends('layouts.dashboard.pathDashboard')
@section('dashboard-content')
<div class="row justify-content-center">
  <div class="card border-left-success shadow h-100">
      <div class="card-header text-primary"><h4><b> متابعة المسارات المفتوحة </b></h4></div>
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
            <div class="card">
              <div class="card-header text-success"><h5><b> المسارات المفتوحة </b></h5></div>     
                <div class="table-responsive"> 
                  @if($paths->count()>0)
                  <table class="table table-secondary table-striped table-bordered">
                      <thead>
                          <tr class="text-center">
                              <th scope="col">الرقم</th>
                              <th scope="col" class="text-nowrap">اسم المسار </th> 
                              <th scope="col" class="text-nowrap">اسم المرحلة الحالية</th>                            
                              <th scope="col" class="text-nowrap">ترتيب المرحلة الحالية</th>
                              <th scope="col" class="text-nowrap">تاريخ بداية المرحلة الحالية </th>
                              <th scope="col" class="text-nowrap">تاريخ انتهاء المرحلة الحالية </th>
                              <th scope="col" class="text-nowrap">نوع الامتحان</th>
                              <th scope="col" class="text-nowrap">تاريخ امتحان المرحلة الحالية</th>
                              <th scope="col" class="text-nowrap">إعادة الامتحان النظري للراسبين</th>
                          </tr>
                      </thead> 
                      <tbody>
                          @foreach ($paths as $path) 
                              <tr class="table-light text-center">
                                <td scope="row">{{$path->id}}</td>
                                <td scope="row">{{$path->path_name}}</td>
                                <td scope="row">{{$path->course_name}}</td>
                                <td scope="row">{{$path->current_stage}}</td>                                
                                <td scope="row">{{$path->course_start_date}}</td> 
                                <td scope="row">{{$path->course_end_date}}</td> 
                                <td scope="row">
                                  @if ($path->exam_type=='theoretical')
                                    نظري
                                  @elseif ($path->exam_type=='practical')
                                    عملي
                                  @elseif ($path->exam_type=='practicalTheoretical')
                                    عملي ونظري معاً    
                                  @endif
                                </td> 
                                <td scope="row">{{$path->exam_start_date}}</td> 
                                <td scope="row">
                                   @if ($path->has_repeat_exam == 1) 
                                    <div class="col">
                                       <a  class="text-sucess" href="">إعادة الامتحان</a> 
                                    </div>
                                   @endif 
                                </td> 
                              </tr>             
                          @endforeach
                      </tbody>
                  </table>
                  @endif
                </div>              
            </div>
        </div>
      </div>
  </div>
</div>
@stop