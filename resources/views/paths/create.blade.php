@extends('layout.main')

@section('title', 'إنشاء المسار')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h2 class="text-primary">إنشاء المسار</h2>
        </div>
        <form class="col-12 mt-3" action="{{route('path.store')}}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">إسم المسار</label>
                <input type="text" class="form-control @error('name') border-danger @enderror" id="name" name="name">
                @error('name')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="idBank" class="form-label">المسار من البنك</label>
                <select class="form-select @error('idBankPath') border-danger @enderror" id="idBank" name="idBankPath">
                <option selected value="0">المسار 1</option>
                <option value="1">المسار 1</option>
                <option value="2">المسار 2</option>
                <option value="3">المسار 1</option>
                </select>
                @error('idBankPath')
                    <div class="text-danger">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">حفظ المسار</button>
        </form>
    </div>
</div>
@endsection
