@extends('Backend.Layouts.app')
@section('content')
    <style>
        .btn-secondary{
            margin-top: 6px;
            margin-left: 500px;
        }
    </style>
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Payment Transfer List</h1>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <a href="{{ route('transfer.menu') }}" class="btn btn-secondary mb-1">
        Back To Transfer
            </a>
        </div>
    </div>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="container mt-4">
                            <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Date</th>
                                            <th>OrderId</th>
                                            <th>Agent</th>
                                            <th>Transfer Amount</th>
                                            <th>Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($payment as $row)
                                            <tr>
                                                <td>{{ $row->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
                                                <td>{{ $row->order_id }}</td>
                                                <td>{{ $row->agent_name }}</td>
                                                <td>{{ $row->transfer_amount }}</td>
                                                <td>{{ $row->remark }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div><!-- .animated -->
    </div><!-- .content -->
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#bootstrap-data-table-export').DataTable({
                "order": [
                    [0, 'desc']
                ]
            });
        });
    </script>
@endsection
