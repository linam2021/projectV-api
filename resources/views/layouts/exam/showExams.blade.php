@extends('layouts.dashboard.examDashboard')
@section('dashboard-content')
<div class="row justify-content-center">
  <div>
    <div class="card border-left-success shadow h-100">
      <div class="card-header text-primary"><h4><b> الامتحانات </b></h4></div>
        <div class="card-body">               
          <div class="table-responsive"> 
            @if($exams->count()>0)
            <table class="table table-secondary table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">الرقم</th>
                        <th scope="col">نوع الامتحان </th>
                        <th scope="col">اسم المرحلة </th>
                        <th scope="col">تاريخ الامتحان </th>
                        <th scope="col">مدة الامتحان </th>
                        <th scope="col">عدد الأسئلة </th>
                        <th scope="col">درجة النجاح </th>
                        <th scope="col">الدرجة العظمى </th>
                    </tr>
                </thead> 
                <tbody>
                    @foreach ($exams as $exam)
                        <tr class="table-light text-center">
                                <td scope="row">{{$exam->id}}</td>
                                @if ($exam->exam_type =="theoretical")
                                  <td scope="row">نظري</td>
                                @elseif ($exam->exam_type =="practical")
                                  <td scope="row">عملي</td>
                                @else
                                  <td scope="row">عملي ونظري</td>
                                @endif  
                                <td scope="row">{{$exam->course_name}}</td>
                                <td scope="row">{{$exam->exam_start_date}}</td>
                                <td scope="row">{{$exam->exam_duration}}</td>
                                <td scope="row">{{$exam->questions_count}}</td>
                                <td scope="row">{{$exam->sucess_mark}}</td>
                                <td scope="row">{{$exam->maximum_mark}}</td>
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