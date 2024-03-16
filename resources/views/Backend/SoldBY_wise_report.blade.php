@extends('Backend.Layouts.app')
@section('content')
<div class="card">
    <div class="card-body">
        <div class="container-fluid">
            <form action="{{ route('sold.report') }}" method="get">
                <div class="row">
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="fromDate" class="form-label">From Date</label>
                            <div class="input-group">
                                <input type="date" name="fromDate" id="fromDate" class="form-control"
                                    value="{{ Request()->fromDate }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="toDate" class="form-label">To Date</label>
                            <div class="input-group">
                                <input type="date" name="toDate" id="toDate" class="form-control"
                                    value="{{ Request()->toDate }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <label class="form-label" for="agent">Agent Name</label>
                        <select id="agent_id" name="agent_id" class="form-control">
                        <option value="">Select Agent Name</option>
                        @foreach ($agentwise as $agents)
                        <option value="{{ $agents->name }}"
                            {{ old('agent_id', Request::get('agent_id')) == $agents->id ? 'selected' : '' }}>
                            {{ $agents->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label class="form-label" for="agent">Client Name</label>
                        <select id="party_id" name="party_id" class="form-control">
                        <option value="">Select Client Name</option>
                        @foreach ($party as $partys)
                        <option value="{{ $partys->name }}"
                            {{ old('party_id', Request::get('party_id')) == $partys->id ? 'selected' : '' }}>
                            {{ $partys->name }}</option>
                        @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1 mt-4">
                        <div class="form-group">
                            <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Search</button>
                        </div>
                    </div>
            </form>
            <div class="col-sm-1 mt-4">
                <div class="form-group">
                    <form action="{{ route('sold.export.csv') }}" method="get">
                        <input type="hidden" name="fromDate" id="hiddenFromDate" class="form-control"
                            value="{{ Request()->fromDate }}">
                        <input type="hidden" name="toDate" id="hiddenToDate" class="form-control"
                            value="{{ Request()->toDate }}">
                        <input type="hidden" name="agent_id" id="hiddenAgent"
                            value="{{ Request::get('agent_id') }}">
                        <input type="hidden" name="party_id" id="hiddenParty"
                            value="{{ Request::get('party_id') }}">
                        <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Export</button>
                    </form>
                </div>
            </div>
            <div class="col-sm-1 mt-4">
                <div class="form-group">
                    <form action="{{ route('sold.generate.pdf') }}" method="get">
                        <input type="hidden" name="fromDate" id="hiddenFromDate" class="form-control"
                            value="{{ Request()->fromDate }}">
                        <input type="hidden" name="toDate" id="hiddenToDate" class="form-control"
                            value="{{ Request()->toDate }}">
                        <input type="hidden" name="agent_id" id="hiddenAgent"
                            value="{{ Request::get('agent_id') }}">
                        <input type="hidden" name="party_id" id="hiddenParty"
                            value="{{ Request::get('party_id') }}">
                        <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Excel To PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
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
                                        <th>SHIPMENTDATE</th>
                                        <th>SHIPMENTMODE</th>
                                        <th>SELLDATE</th>
                                        <th>AGENTNAME</th>
                                        <th>SOLDBY</th>
                                        <th>CLIENT</th>
                                        <th>CONTACTNO</th>
                                        <th>TOTAL</th>
                                        <th>AMOUNT</th>
                                        <th>WEIGHT</th>
                                        <th>ACCEPTWEIGHT</th>
                                        <th>REMAININGWEIGHT</th>
                                        <th>Deliverd Date</th>
                                        <th>Payment Mode</th>
                                        <th>SHAPE</th>
                                        <th>COLOR</th>
                                        <th>CLARITY</th>
                                        <th>CUT</th>
                                        <th>POL</th>
                                        <th>SYMM</th>
                                        <th>FLORO</th>
                                        <th>LAB</th>
                                        <th>LABNO</th>
                                        <th>PCS</th>
                                        <th>RATE</th>
                                        <th>MM1</th>
                                        <th>MM2</th>
                                        <th>MM3</th>
                                        <th>TABLE</th>
                                        <th>TD</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sold as $row)
                                    <tr>
                                        <td>{{ $row->shipment_date }}</td>
                                        <td>{{ $row->shipment_mode }}</td>
                                        <td>{{ $row->sell_date }}</td>
                                        <td>{{ $row->agent }}</td>
                                        <td>{{ $row->sold_by}}</td>
                                        <td>{{ $row->client }}</td>
                                        <td>{{ $row->contact_no }}</td>
                                        <td>{{ $row->total }}</td>
                                        <td>{{ $row->amount }}</td>
                                        <td>{{ $row->tweight }}</td>
                                        <td>{{ $row->weight }}</td>
                                        <td>{{ $row->remaining_weight }}</td>
                                        <td>{{ $row->delivery_date }}</td>
                                        <td>{{ $row->payment_mode }}</td>
                                        <td>{{ $row->shape }}</td>
                                        <td>{{ $row->color }}</td>
                                        <td>{{ $row->clarity }}</td>
                                        <td>{{ $row->cut }}</td>
                                        <td>{{ $row->pol }}</td>
                                        <td>{{ $row->symm }}</td>
                                        <td>{{ $row->floro }}</td>
                                        <td>{{ $row->lab }}</td>
                                        <td>{{ $row->lab_no }}</td>
                                        <td>{{ $row->pcs }}</td>
                                        <td>{{ $row->rate }}</td>
                                        <td>{{ $row->mm1 }}</td>
                                        <td>{{ $row->mm2 }}</td>
                                        <td>{{ $row->mm3 }}</td>
                                        <td>{{ $row->table }}</td>
                                        <td>{{ $row->td }}</td>
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
    <script>
        $(document).ready(function() {
            $('#fromDate').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenFromDate').val(selectedDate);
            });
            $('#toDate').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenToDate').val(selectedDate);
            });
            $('#agent').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenAgent').val(selectedDate);
            });
            $('#party').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenParty').val(selectedDate);
            });
        });
    </script>

@endsection
