@extends('layouts.dashboard.homeDashboard')
@section('dashboard-content')
  <!-- Content Row -->
  <div class="row">

    <!-- Heros count Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            عدد الأبطال
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$userCount}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-user-friends fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Admins count Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            عدد المدراء
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$adminCount}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-user-alt fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Paths count Card -->
    <div class="col-md-4 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            عدد المسارات المفتوحة
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{$openPathCount}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fa fa-university fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 <!-- Content Row -->
<div class="row">
  <!-- Content Column -->
  <div class="col-lg-12 col-md-12 mb-4">
      <div class="card shadow mb-4">
          <div class="card-header py-3">
              <h6 class="m-0 font-weight-bold text-info">تقدم المسارات المفتوحة</h6>
          </div>
          <div class="card-body">
            @foreach ($pathsProgress as $path)
                <h4 class="small font-weight-bold">{{$path->path_name}}&nbsp;
                    <span class="float-right">%{{(int)$path->course_stage_count}}</span></h4>
                <div class="progress mb-4">
                    <div class="progress-bar bg-success" role="progressbar" style="width: {{(int)$path->course_stage_count}}%"
                        aria-valuenow={{$path->course_stage_count}} aria-valuemin="0" aria-valuemax="100">
                    </div>
                 </div>
            @endforeach

            @if($pathsProgress->count()>0)
            <table class="table table-secondary table-striped table-bordered">
                <thead>
                    <tr class="text-center">
                        <th scope="col">اسم المسار</th>
                        <th scope="col">نسبة التقدم </th>
                        
                    </tr>
                </thead> 
                <tbody>
                    @foreach ($pathsProgress as $p)
                        <tr class="table-light text-center">
                                <td scope="row">{{$p->path_name}}</td>                             
                                <td scope="row">%{{(int)$p->course_stage_count}}</td>                   
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
