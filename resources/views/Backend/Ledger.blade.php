@extends('Backend.Layouts.app')
@section('content')
    <style>
        .col-sm-1 {
            padding-left: 5px !important;
        }
    </style>
    <div class="breadcrumbs">
        <form action="{{ route('ledger') }}" method="get">
            <div class="col-sm-2" style="margin-top: 19px;">
                <div class="form-group">
                    <label for="fromDate" class="form-label"><strong>From Date</strong></label>
                    <div class="input-group">
                        <input type="date" name="fromDate" id="fromDate" class="form-control"
                            value="{{ Request()->fromDate }}">
                    </div>
                </div>
            </div>

            <div class="col-sm-2" style="margin-top: 19px;">
                <div class="form-group">
                    <label for="toDate" class="form-label"><strong>To Date</strong></label>
                    <div class="input-group">
                        <input type="date" name="toDate" id="toDate" class="form-control"
                            value="{{ Request()->toDate }}">
                    </div>
                </div>
            </div>

            <div class="col-sm-2" style="margin-top: 19px;">
                <label class="form-label" for="agent"><strong>Agent Name</strong></label>
                <select id="agent_id" name="agent_id" class="form-control">
                    <option value="">Select Agent Name</option>
                    @foreach ($agent as $agents)
                        <option value="{{ $agents->id }}"
                            {{ old('agent_id', Request::get('agent_id')) == $agents->id ? 'selected' : '' }}>
                            {{ $agents->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2" style="margin-top: 19px;">
                <label class="form-label" for="agent"><strong>Client Name</strong></label>
                <select id="party_id" name="party_id" class="form-control">
                    <option value="">Select Client Name</option>
                    @foreach ($party as $partys)
                        <option value="{{ $partys->name }}"
                            {{ old('party_id', Request::get('party_id')) == $partys->name ? 'selected' : '' }}>
                            {{ $partys->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2" style="margin-top: 19px;">
                <div class="form-group">
                    <label for="fromDate" class="form-label"><strong>Total Wallet</strong></label>
                    <div class="input-group">
                        <input type="number" name="total_wallet" id="total_wallet" class="form-control"
                            value="" readonly>
                    </div>
                </div>
            </div>

            <div class="col-sm-2 mt-5" style="padding: 4px !important;">
                <div class="form-group">
                    <button type="submit" class="btn btn-secondary">Search</button>
                    <a href="{{ route('ledger') }}">
                        <button type="button" class="btn btn-secondary">Refresh</button>
                    </a>
                </div>
            </div>

        </form>
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
                                            <th>AgentName</th>
                                            <th>PartyName</th>
                                            <th>Amount</th>
                                            <th>Wallet</th>
                                            <th>Reason</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $totalAmount = 0;
                                        @endphp
                                        @foreach ($collection as $row)
                                        <tr>
                                            <td>{{ $row->id }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row->date)->format('d-m-Y') }}</td>
                                            <td>{{ $row->agent_name }}</td>
                                            <td>{{ $row->party_id }}</td>
                                            <td>{{ number_format($row->amount, 2) }}</td>
                                            <td>{{ number_format($row->wallet, 2) }}</td>
                                            @php
                                            $totalAmount += $row->wallet;
                                            @endphp
                                            <td>{{ $row->reason }}</td>
                                            <td>{{ $row->check }}</td>
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
            var formattedTotalAmount = parseFloat({{ $totalAmount }}).toFixed(2);
            $('#total_wallet').val(formattedTotalAmount);
        });
    </script>
@endsection
