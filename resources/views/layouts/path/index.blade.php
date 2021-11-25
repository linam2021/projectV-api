@extends('layouts.dashboard.pathDashboard')
@section('dashboard-content')
<div class="row justify-content-center">
  <div class="card border-left-success shadow h-100">
      <div class="card-header text-primary"><h4><b> المسارات </b></h4></div>
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
              <div class="card-header text-success"><h5><b> المسارات المتوفرة </b></h5></div>     
                <div class="table-responsive"> 
                  @if($paths->count()>0)
                  <table class="table table-secondary table-striped table-bordered">
                      <thead>
                          <tr class="text-center">
                              <th scope="col">الرقم</th>
                              <th scope="col" class="text-nowrap">اسم المسار </th>
                              <th scope="col" class="text-nowrap" >صورة المسار </th>
                              <th scope="col" class="text-nowrap">تاريخ البدء بالمسار</th>
                              <th scope="col" class="text-nowrap">المرحلة الحالية </th>
                              <th scope="col" class="text-nowrap">البدء بالمسار </th>
                              <th scope="col" class="text-nowrap">القبول بالمسار </th>
                              <th scope="col">المراحل</th>
                              <th scope="col">حذف</th>
                          </tr>
                      </thead> 
                      <tbody>
                          @foreach ($paths as $path) 
                              <tr class="table-light text-center">
                                <td scope="row">{{$path->id}}</td>
                                <td scope="row">{{$path->path_name}}</td>
                                <td scope="row"><a href={{route('loadImage',['name'=>$path->path_image_name])}} class="link-primary">Link</a></td>
                                <td scope="row">{{$path->path_start_date}}</td>
                                <td scope="row">
                                  @if (($path->current_stage==0) and ($path->path_start_date==null)and (!$path->hasCourses))
                                      {{-- do nothing --}}
                                  @elseif (($path->current_stage==0) and ($path->path_start_date==null))
                                     المسار مغلق                
                                  @elseif (($path->current_stage==0) and ($path->path_start_date!=null)) 
                                      فترة التسجيل على المسار
                                  @elseif ($path->current_stage==-1)  
                                      انتظار بدء المسار  
                                  @elseif ($path->current_stage>0)  
                                      {{$path->current_stage}}
                                  @endif    
                                </td> 
                                <td scope="row" class="text-nowrap">
                                  @if (($path->current_stage==0) and ($path->path_start_date==null)and (!$path->hasCourses))
                                      {{-- do nothing --}}
                                  @elseif (($path->current_stage==0) and ($path->path_start_date==null))
                                    <div class="col">
                                      <a  class="text-primary" href="{{route('path.startRegisterInPath',['id'=>$path->id])}}">البدء بالتسجيل </a>                                   
                                    </div>
                                  @elseif (($path->current_stage==0) and ($path->path_start_date!=null))   
                                    <div class="col">
                                        <a  class="text-sucess" href="{{route('path.finishRegister',['id'=>$path->id])}}">إنهاء فترة التسجيل </a>                          
                                    </div>
                                  @elseif (($path->current_stage==-3))   
                                    <div class="col">
                                        <a  class="text-sucess" href="{{route('path.examPreparation',['id'=>$path->id])}}">إعداد امتحان المرحلة الأولى </a>                          
                                    </div>
                                  @elseif (($path->current_stage==-2)) 
                                    {{-- do nothing --}}
                                  @elseif (($path->current_stage==-1))   
                                    <div class="col">
                                        <a  class="text-sucess" href="{{route('path.startPath',['id'=>$path->id])}}">البدء بالمسار </a>                          
                                    </div>
                                  @endif   
                                </td> 
                                <td scope="row">
                                  @if ($path->current_stage==-2)  
                                    <div class="col">
                                      <a  class="text-sucess" href="{{route('path.applicantsUsers',['id'=>$path->id])}}">القبول  </a>                          
                                    </div>
                                  @endif   
                                </td> 
                                <td>
                                  <div class="row" >
                                    @if ($path->hasCourses)
                                      <div class="col">
                                        <a  title="إظهار المراحل ضمن المسار" class="text-dark" href="{{route('course.index',['id'=>$path->id])}}"> <i class="fas fa-eye"> </i></a>                                   
                                      </div>
                                      <div class="col">                                        
                                      </div>
                                    @else
                                      <div class="col">                                          
                                      </div>
                                      <div class="col">
                                        <a  title="إضافة مراحل إلى المسار" class="text-dark" href="{{route('course.create',['id'=>$path->questionbank_path_id])}}"> <i class="fa fa-plus-circle"> </i></a>
                                      </div>
                                    @endif
                                  </div>
                                </td>
                                <td>
                                  {{-- @if (!$path->hasCourses) --}}
                                  <div class="col">
                                    <a title="حذف المسار" class="text-danger" href="{{route('paths.destroy',['id'=>$path->id])}}" > <i class="fas fa-trash-alt"></i></a>    
                                  </div>
                                  {{-- @endif --}}
                                </td>
                              </tr>             
                          @endforeach
                      </tbody>
                  </table>
                  @endif
                </div>              
            </div>

            <div class="card">
              <div class="card-header text-danger"><h5><b> المسارات المحذوفة </b></h5></div>                        
                <div class="table-responsive"> 
                  @if($paths->count()>0)
                  <table class="table table-secondary table-striped table-bordered">
                      <thead>
                          <tr class="text-center">
                              <th scope="col">الرقم</th>
                              <th scope="col">اسم المسار </th>
                              <th scope="col">المرحلة الحالية </th>
                              <th scope="col">المراحل</th>
                              <th scope="col">&nbsp;&nbsp;&nbsp;&nbsp; </th>
                          </tr>
                      </thead> 
                      <tbody>
                          @foreach ($trashedPaths as $path) 
                              <tr class="table-danger text-center">
                                <td scope="row">{{$path->id}}</td>
                                <td scope="row">{{$path->path_name}}</td>
                                <td scope="row">{{$path->current_stage}}</td>
                                <td>
                                  <div class="row" >
                                    @if ($path->hasCourses)
                                      <div class="col">
                                        <a  class="text-dark" href="#"> <i class="fas fa-eye"> </i></a>                                   
                                      </div>
                                      <div class="col">                                        
                                      </div>
                                    @else
                                      <div class="col">                                       
                                      </div>
                                      <div class="col">                                        
                                      </div>
                                    @endif
                                  </div>
                                </td>
                                <td>
                                  <div class="col">                                   
                                  </div>
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