@extends('layouts.dashboard.pathDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>البدء بالتسجيل على المسار &nbsp;{{$path->path_name}}</b></h4></div>
          <div class="card-body">
            <form action="{{route('path.setStartRegisterPath',['id'=>$path->id])}}" method="POST" enctype="multipart/form-data">
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
                    <div class="col-md-5">  
                        <div class="form-group">
                            <div class='input-group date' id='datepicker'>
                                <label for="pathname" class="form-label">  تاريخ البدء بالمسار</label>
                                &nbsp; &nbsp;&nbsp;
                                <input name="startDate" type='text' class="form-control" readonly>
                                <label class="input-group-addon" for="dateField">
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">موافق</button>
                    </div>
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
