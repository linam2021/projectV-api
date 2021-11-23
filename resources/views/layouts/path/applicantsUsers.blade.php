@extends('layouts.dashboard.pathDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>  اختيار عدد المقبولين في المسار &nbsp;{{$path->path_name}}</b></h4></div>
          <div class="card-body">
            <form action="{{route('path.acceptUsers',['id'=>$path->id,'count'=>$user_path_count])}}" method="POST" >
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
                <div class="row align-items-center ">
                    <div class="col-md-5">  
                        <div class="form-group">
                            <div >
                                <label for="pathname" class="form-label">عدد المسجلين حاليا في المسار</label>
                                <input name="startDate" type='text' class="form-control" value = {{$user_path_count}} readonly>
                            </div>
                            <div >
                                <label for="pathname" class="form-label">حدد العدد الأعظمي للمقبولين في المسار</label>
                                <input name="acceptUsersCount" type='text' class="form-control" pattern="\d+" size =3 maxlength="5" class="text-center" required>    
                            </div>
                        </div>
                    </div>                   
                </div>
                <div class="col-md-12 text-center my-3">
                    <button type="submit" class="btn btn-primary">موافق</button>
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
@stop
