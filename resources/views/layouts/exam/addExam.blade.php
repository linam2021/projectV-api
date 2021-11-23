@extends('layouts.dashboard.pathDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
  <div>
    <div class="card border-left-success shadow h-100">
      <div class="card-header text-primary"><h4><b>إضافة امتحان</b></h4></div>
        <div class="card-body">
          <form action="{{route('exam.add',['id'=>$course_id])}}" method="POST">
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
              <div class="row align-items-center">            
                    <div class="col-md-6 my-3"> 
                        <div class="form-group">
                            <div class='input-group' >
                                <label for="pathname" class="form-label"> نوع الامتحان</label> 
                                &nbsp; &nbsp;&nbsp;                      
                                <select id =exam_type  value="{{ old('exam_type') }}" name =exam_type class="form-select" name =exam_type onchange="showHideElement()">
                                    @if (old('exam_type')=='practical')
                                        <option value="theoretical">نظري</option>
                                        <option selected value="practical">عملي</option>
                                        <option value="practicalTheoretical">عملي ونظري معاً</option> 
                                    @elseif (old('exam_type')=='practicalTheoretical')
                                        <option value="theoretical">نظري</option>
                                        <option value="practical">عملي</option>
                                        <option selected value="practicalTheoretical">عملي ونظري معاً</option>
                                    @else 
                                         <option selected value="theoretical">نظري</option>
                                         <option value="practical">عملي</option>
                                         <option value="practicalTheoretical">عملي ونظري معاً</option>     
                                    @endif
                                </select>                               
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 my-3">  
                        <div class="form-group">
                            <div class='input-group date' id='datepicker'>
                                @if ((old('exam_type')=='practical') || old('exam_type')=='practicalTheoretical' ) 
                                    <label id =exam_date_label class="form-label">تاريخ الامتحان العملي</label>
                                @else
                                    <label id =exam_date_label class="form-label">تاريخ الامتحان</label>
                                @endif
                                &nbsp; &nbsp;&nbsp;
                                <input id="exam_start_date" value="{{ old('exam_start_date') }}" name="exam_start_date" type='text' class="form-control" readonly>
                                <label class="input-group-addon" for="dateField">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 my-3">  
                        <div class="form-group">
                            <div class='input-group'>
                                <label class="form-label">الدرجة العظمى</label>                                
                                &nbsp; &nbsp;&nbsp;
                                <input id =maximum_mark name="maximum_mark" id="maximum_mark" value="{{ old('maximum_mark') }}" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 my-3">  
                        <div class="form-group">
                            <div class='input-group'>
                                <label class="form-label">درجة النجاح</label>                                
                                &nbsp; &nbsp;&nbsp;
                                <input id = sucess_mark name="sucess_mark" id="sucess_mark" value="{{ old('sucess_mark') }}" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>    
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 my-3">  
                        <div class="form-group">
                            <div class='input-group'>
                                @if ((old('exam_type')=='practical') || old('exam_type')=='practicalTheoretical' )                               
                                    <label hidden id=questions_count_label class="form-label">عدد الأسئلة الرئيسية</label>                                
                                    &nbsp; &nbsp;&nbsp;
                                    <input id=questions_count id="questions_count" value="{{ old('questions_count') }}" type="hidden" name="questions_count" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required> 
                                @else
                                    <label id=questions_count_label class="form-label">عدد الأسئلة الرئيسية</label>                                
                                    &nbsp; &nbsp;&nbsp;
                                    <input id=questions_count id="questions_count" value="{{ old('questions_count') }}" type="input" name="questions_count" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>          
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 my-3">  
                        <div class="form-group">
                            <div class='input-group'>
                                @if ((old('exam_type')=='practical') || old('exam_type')=='practicalTheoretical' )                               
                                    <label hidden id =exam_duration_label class="form-label">مدة الامتحان (بالدقائق)</label>                                
                                    &nbsp; &nbsp;&nbsp;
                                    <input id=exam_duration id="exam_duration" value="{{ old('exam_duration') }}"type="hidden" name="exam_duration" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>    
                                @else
                                    <label id =exam_duration_label class="form-label">مدة الامتحان (بالدقائق)</label>                                
                                    &nbsp; &nbsp;&nbsp;
                                    <input id=exam_duration id="exam_duration" value="{{ old('exam_duration') }}"type="input" name="exam_duration" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>    
                                @endif
                            </div>
                        </div>
                    </div>
              </div>  
              <div class="col-md-12 text-center my-3">
                  <button type="submit" class="btn btn-primary" onclick="checkMark()">إضافة</button>
              </div>
          </form>
        </div>
      </div>
  </div>
</div>

<script type="text/javascript">
$(function() {
    $('#datepicker').datepicker();
});
</script>
<script src="{{URL::asset('js/exam.js')}}"></script>
@stop
