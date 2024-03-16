@extends('Backend.Layouts.app')
@section('content')
@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Excel Upload</h1>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">

                            <div class="col-md-3">
                                <label class="form-label">Upload Excel File</label>
                                <input type="file" id="excelFile" name="excelFile" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Date</label>
                                <input type="date" id="date" name="date" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Shipment Mode</label>
                                <select id="shipment_mode" name="shipment_mode" class="form-control">
                                    <option value="">Please Select Shipment</option>
                                    @foreach ($shipment as $row)
                                        <option value="{{ $row->name }}">{{ $row->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-1 mt-4">
                            <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Submit</button>
                            </div>
                            <div class="col-md-1 mt-4">
                                <a href="{{ route('download.file', ['filename' => 'sample.csv']) }}" class="btn btn-secondary" style="margin-top: 8px;" download>Format Download</a>
                            </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
