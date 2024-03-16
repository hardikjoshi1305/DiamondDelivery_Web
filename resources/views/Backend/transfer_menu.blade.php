@extends('Backend.Layouts.app')
@section('content')
    <style>
        .select2-container--default .select2-selection--single {
            background-color: #fff;
            border: 1px solid #aaa;
            border-radius: 4px;
            width: 460px !important;
            padding: 17px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #888 transparent transparent transparent;
            border-style: solid;
            border-width: 5px 4px 0 4px;
            height: 0;
            left: 1756% !important;
            margin-left: -4px;
            margin-top: 3px !important;
            position: absolute;
            top: 50%;
            width: 0;
        }

        .select2-dropdown {
            width: 460px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            /* color: #444; */
            line-height: 29px !important;
            margin-top: -15px !important;
        }
    </style>
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <form action="{{ route('transfer.menu') }}" method="get">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="fromDate" class="form-label"><strong>From Date</strong></label>
                                <div class="input-group">
                                    <input type="date" name="fromDate" id="fromDate" class="form-control"
                                        value="{{ Request()->fromDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>To Date</strong></label>
                                <div class="input-group">
                                    <input type="date" name="toDate" id="toDate" class="form-control"
                                        value="{{ Request()->toDate }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
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

                        <div class="col-sm-3">
                            <label class="form-label" for="agent"><strong>Client Name</strong></label>
                            <select id="party_id" name="party_id" class="form-control">
                                <option value="">Select Client Name</option>
                                @foreach ($party as $partys)
                                    <option value="{{ $partys->name }}"
                                        {{ old('party_id', Request::get('party_id')) == $partys->id ? 'selected' : '' }}>
                                        {{ $partys->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <label for="type" class="form-label"><strong>Type</strong></label>
                            <select name="type" id="type" class="form-control">
                                <option value="all" {{ Request()->type == 'all' ? 'selected' : '' }}>All</option>
                                <option value="credit" {{ Request()->type == 'credit' ? 'selected' : '' }}>Credit</option>
                                <option value="debit" {{ Request()->type == 'debit' ? 'selected' : '' }}>Debit</option>
                            </select>
                        </div>
                        <div class="col-sm-1 mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary" style="margin-top: 5px;">Search</button>
                            </div>
                        </div>
                        <div class="col-sm-1 mt-4">
                            <a href="{{ route('transfer.menu') }}">
                                <button type="button" class="btn btn-secondary" style="margin-top: 5px;">Refresh</button>
                            </a>
                        </div>
                        <div class="col-sm-1 mt-4">
                            <button type="button" class="btn btn-secondary" style="margin-top: 5px;" id="mdlshow">
                                <i class="fa fa-plus mr-2"></i>Send Money
                            </button>
                        </div>
                </form>
            </div>
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
                                            <th>View</th>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Payment</th>
                                            <th>AgentName</th>
                                            <th>PartyName</th>
                                            <th>ItemName</th>
                                            <th>Amount</th>
                                            <th>Weight</th>
                                            <th>Remark</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($diamond as $diamonds)
                                            <tr>
                                                <td>{{ $diamonds->id }}</td>
                                                <td> <a href="{{ route('payment.list', ['id' => $diamonds->id]) }}"
                                                        class="btn btn-primary">View</a></td>
                                                <td>{{ \Carbon\Carbon::parse($diamonds->date)->format('d-m-Y') }}</td>
                                                <td>{{ $diamonds->type }}</td>
                                                <td>{{ $diamonds->payment }}</td>
                                                <td>{{ $diamonds->agent_name }}</td>
                                                <td>{{ $diamonds->party_id }}</td>
                                                <td>{{ $diamonds->item_id }}</td>
                                                <td>{{ number_format($diamonds->amount, 2) }}</td>
                                                <td>{{ $diamonds->weight }}</td>
                                                <td>{{ $diamonds->reason }}</td>

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




    <div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="smallmodalLabel" style="display: none;">

        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form method="POST" action="{{ route('transfer.store') }}" id="addagent" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hidden_id" name="hidden_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="smallmodalLabel">Add Agent</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                placeholder="Enter Date">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Agent Name</label>
                            <br>
                            <select class="form-control" id="name" name="name">
                                <option value="">Select Agent</option>
                                @foreach ($agent as $row)
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Remark</label>
                            <input type="text" class="form-control" id="reason" name="reason"
                                placeholder="Enter Remark">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveremark" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
                ] // Order by the first column (index 0) in descending order
            });
        });
    </script>
    <script>
        var SITEURL = '{{ URL::to('') }}';
        $(document).ready(function() {

            $("#mdlshow").click(function() {
                $('#smallmodalLabel').html("Add Remark");
                $('#hidden_id').val("");
                $('#name').val("");
                $('#mob').val("");
                $('#password').val("");

                $('#agentModal').modal('toggle');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#name').select2({
                placeholder: 'Select Agent Name',
                allowClear: true, // optional

            });
        });
    </script>
@endsection
