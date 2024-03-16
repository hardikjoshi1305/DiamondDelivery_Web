@extends('Backend.Layouts.app')
@section('content')

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Notification</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered" data-sort-name="Date"  data-order='[[ 0, "desc" ]]' data-sort-order="desc">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Notification</th>
                                            <th>Amount</th>
                                            <th>Client</th>
                                            <th>Weight</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($notification as $row)
                                        <tr>
                                        <td>{{ $row->id }}</td>
                                            <td> <strong>Order Accepted By</strong> ({{ $row->agent_name }})</td>
                                            <td>{{ $row->amount }}</td>
                                            <td>{{ $row->client_name }}</td>
                                            <td>{{ $row->weight }}</td>
                                        </tr>

                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
