@extends('layouts.dashboard.pathDashboard')

@section('dashboard-content')
<div class="row justify-content-center">
    <div>
      <div class="card border-left-success shadow h-100">
        <div class="card-header text-primary"><h4><b>إضافة مسار</b></h4></div>
          <div class="card-body">
            <form action="{{route('path.store')}}" method="POST" enctype="multipart/form-data">
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
                    <label for="pathname" class="form-label"> اسم المسار في موقع بنك الأسئلة</label>
                    <div class="col-md-10 mr-2">                        
                        <select class="form-select" name =idBankPath aria-label="Default select example" >
                            @for ($i =0 ; $i<count($questionBankPaths['data']); $i++)
                                <option value="{{$questionBankPaths['data'][$i]['id']}}">{{$questionBankPaths['data'][$i]['path_name']}}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">إضافة</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
    </div>
</div>
@stop
