@extends('layouts.dashboard.pathDashboard')
@section('dashboard-content')

<div class="row justify-content-center">
    <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>إضافة مراحل</b></h4></div>
          <div class="card-body">            
            <form action="{{ route('course.store', ['id'=>$path->id,'qbid'=>$path->questionbank_path_id])}}" method="POST" enctype="multipart/form-data">
              @csrf
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
                  <div class="card-header text-success"><h5><b> المراحل من بنك الأسئلة </b></h5></div>     
                    <div class="table-responsive"> 
                      <table class="table table-secondary table-striped table-bordered">
                          <thead>
                              <tr class="text-center">
                                  <th scope="col">الرقم</th>
                                  <th scope="col" class="text-nowrap">اسم المرحلة </th>
                                  <th scope="col" class="text-nowrap">رابط المرحلة </th>
                                  <th scope="col" class="text-nowrap">المدة (يوم)</th>
                                  <th scope="col" class="text-nowrap"> ترتيب المرحلة</th>
                              </tr>
                          </thead> 
                          <tbody>
                              @for ($i =0 ; $i<count($questionBankPathCourses['data']); $i++)
                                  <tr class="table-light text-center">                                    
                                    <td scope="row">
                                      <input type="hidden" class="form-control" value="{{$questionBankPathCourses['data'][$i]['id']}}">
                                      <input type="hidden" class="form-control" name = "qBid{{$i}}">                                       
                                      <input type="checkbox"  id= "id{{$i}}" onclick="oncheck(this)">
                                    </td>
                                    <td scope="row">
                                      <p>{{$questionBankPathCourses['data'][$i]['course_name']}}</p>
                                      <input type="hidden" class="form-control" name = "course_name{{$i}}">  
                                    </td>
                                    <td scope="row" class="col-1 text-wrap text-end">    
                                      <a  href="{{$questionBankPathCourses['data'][$i]['course_link']}}">{{$questionBankPathCourses['data'][$i]['course_link']}}</a>
                                      <input type="hidden" class="form-control" name="course_link{{$i}}">                                     
                                    </td>
                                    <td scope="row">
                                      <input type="text" pattern="\d+" size =3 maxlength="2" class="text-center" class="form-control" name="course_duration{{$i}}" disabled>
                                    </td>
                                    <td scope="row">
                                      <input type="text" pattern="\d+" size =3 maxlength="2" class="text-center" class="form-control" name="stage{{$i}}" disabled>
                                    </td> 
                                  </tr>           
                              @endfor
                          </tbody>
                      </table>
                    </div>              
                  </div>
              </div>
              <div class="col-md-12 text-center my-2">
                <button type="submit" class="btn btn-primary">إضافة</button>
              </div>
            </form>
          </div>
        </div>
    </div>
</div>
@endsection
