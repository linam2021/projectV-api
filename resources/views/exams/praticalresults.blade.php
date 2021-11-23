@extends('layouts.dashboard.examDashboard')
@section('dashboard-content')
<div class="row">
    @if ($message = Session::get('message'))
    <div class="alert alert-success" role="alert">
        {{$message}}
    </div>
    @endif
</div>
<div class="row">
    <h2 class="col-12 text-primary">رفع نتائج الإختبار التطبيقي</h2>
    <form class="col-12 mt-3" method="post" action="{{route('praticalresults.import')}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="formFile" class="form-label">المسارات</label>
            <select class="form-select @error('path_id') border-danger @enderror" name="path_id">
                @foreach ($paths as $item)
                <option value="{{$item->id}}">{{$item->path_name}}</option>
                @endforeach
            </select>
            @error('path_id')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="uploadFile" class="form-label">رفع ملف الإكسل</label>
            <input class="form-control @error('sheet') border-danger @enderror" type="file" id="uploadFile" name="sheet">
            @error('sheet')
                <div class="text-danger">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">إضافة النقاط</button>
    </form>
</div>
@endsection
