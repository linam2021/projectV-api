@extends('layouts.dashboard.examDashboard')
@section('dashboard-content')
<div class="row">
    <h2 class="col-12 text-primary">رفع نتائج الإختبار التطبيقي</h2>
    <form class="col-12 mt-3" method="post" action="{{route('praticalresults.import')}}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="formFile" class="form-label">المسارات</label>
            <select class="form-select" name="path_id">
                @foreach ($paths as $item)
                <option value="{{$item->id}}">{{$item->path_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="uploadFile" class="form-label">رفع ملف الإكسل</label>
            <input class="form-control" type="file" id="uploadFile" name="sheet">
        </div>
        <button type="submit" class="btn btn-primary">رفع الملف</button>
    </form>
</div>
@endsection
