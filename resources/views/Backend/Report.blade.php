@extends('Backend.Layouts.app')
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="container-fluid">
                <form action="{{ route('report') }}" method="get">
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
                        <div class="col-sm-2">
                            <label class="form-label" for="agent"><strong>Agent Name</strong></label>
                            <select id="agent_id" name="agent_id" class="form-control">
                                <option value="">Select Agent Name</option>
                                @foreach ($agent as $agents)
                                    <option value="{{ $agents->name }}"
                                        {{ old('agent_id', Request::get('agent_id')) == $agents->id ? 'selected' : '' }}>
                                        {{ $agents->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
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
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>Remaining Weight</strong></label>
                                <div class="input-group">
                                    <input type="text" name="remaining_weight" id="remaining_weight" class="form-control"
                                        value="{{ Request()->remaining_weight }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>Color</strong></label>
                                <div class="input-group">
                                    <input type="text" name="color" id="color" class="form-control"
                                        value="{{ Request()->color }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>Shape</strong></label>
                                <div class="input-group">
                                    <input type="text" name="shape" id="shape" class="form-control"
                                        value="{{ Request()->shape }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>From Weight</strong></label>
                                <div class="input-group">
                                    <input type="text" name="from_weight" id="from_weight" class="form-control"
                                        value="{{ Request()->from_weight }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="toDate" class="form-label"><strong>To Weight</strong></label>
                                <div class="input-group">
                                    <input type="text" name="to_weight" id="to_weight" class="form-control"
                                        value="{{ Request()->to_weight }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-1 mt-4">
                            <div class="form-group">
                                <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Search</button>
                            </div>
                        </div>
                        <div class="col-sm-1 mt-4">
                            <a href="{{ route('report') }}">
                                <button type="button" class="btn btn-secondary" style="margin-top: 8px;">Refresh</button>
                            </a>
                        </div>
                </form>
                <div class="col-sm-1 mt-4">
                    <div class="form-group">
                        <form action="{{ route('export.csv') }}" method="get">
                            <input type="hidden" name="fromDate" id="hiddenFromDate" class="form-control"
                                value="{{ Request()->fromDate }}">
                            <input type="hidden" name="toDate" id="hiddenToDate" class="form-control"
                                value="{{ Request()->toDate }}">
                            <input type="hidden" name="agent_id" id="hiddenAgent"
                                value="{{ Request::get('agent_id') }}">
                            <input type="hidden" name="party_id" id="hiddenParty"
                                value="{{ Request::get('party_id') }}">
                            <input type="hidden" name="remaining_weight" id="hiddenweight"
                                value="{{ Request::get('remaining_weight') }}">
                            <input type="hidden" name="color" id="hiddencolor" value="{{ Request::get('color') }}">
                            <input type="hidden" name="shape" id="hiddenshape" value="{{ Request::get('shape') }}">
                            <input type="hidden" name="from_weight" id="hiddenfromweight"
                                value="{{ Request::get('from_weight') }}">
                            <input type="hidden" name="to_weight" id="hiddentoweight"
                                value="{{ Request::get('to_weight') }}">
                            <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Export</button>
                        </form>
                    </div>
                </div>
                <div class="col-sm-1 mt-4">
                    <div class="form-group">
                        <form action="{{ route('generate.pdf') }}" method="get">
                            <input type="hidden" name="fromDate" id="hiddenFromDate" class="form-control"
                                value="{{ Request()->fromDate }}">
                            <input type="hidden" name="toDate" id="hiddenToDate" class="form-control"
                                value="{{ Request()->toDate }}">
                            <input type="hidden" name="agent_id" id="hiddenAgent"
                                value="{{ Request::get('agent_id') }}">
                            <input type="hidden" name="party_id" id="hiddenParty"
                                value="{{ Request::get('party_id') }}">
                            <input type="hidden" name="remaining_weight" id="hiddenweight"
                                value="{{ Request::get('remaining_weight') }}">
                            <input type="hidden" name="color" id="hiddencolor" value="{{ Request::get('color') }}">
                            <input type="hidden" name="shape" id="hiddenshape" value="{{ Request::get('shape') }}">
                            <input type="hidden" name="from_weight" id="hiddenfromweight"
                                value="{{ Request::get('from_weight') }}">
                            <input type="hidden" name="to_weight" id="hiddentoweight"
                                value="{{ Request::get('to_weight') }}">
                            <button type="submit" class="btn btn-secondary" style="margin-top: 8px;">Excel TO
                                PDF</button>
                        </form>
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
                        <div class="container mt-4 mb-5">
                            <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>SHIPMENTDATE</th>
                                            <th>SHIPMENTMODE</th>
                                            <th>SNNO</th>
                                            <th>SELLDATE</th>
                                            <th>SOLDBY</th>
                                            <th>CLIENT</th>
                                            <th>CONTACTNO</th>
                                            <th>TOTAL</th>
                                            <th>WEIGHT</th>
                                            <th>WEIGHT</th>
                                            <th>REMAININGWEIGHT</th>
                                            <th>AMOUNT</th>
                                            <th>REASON</th>
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
                                            <th>AGENTNAME</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($diamonddetails as $diamonddetail)
                                            <tr>
                                                <td>{{ $diamonddetail->shipment_date }}</td>
                                                <td>{{ $diamonddetail->shipment_mode }}</td>
                                                <td>{{ $diamonddetail->sn_no }}</td>
                                                <td>{{ $diamonddetail->sell_date }}</td>
                                                <td>{{ $diamonddetail->sold_by }}</td>
                                                <td>{{ $diamonddetail->client }}</td>
                                                <td>{{ $diamonddetail->contact_no }}</td>
                                                <td>{{ $diamonddetail->total }}</td>
                                                <td>{{ $diamonddetail->tweight }}</td>
                                                <td>{{ $diamonddetail->weight }}</td>
                                                <td>{{ $diamonddetail->remaining_weight }}</td>
                                                <td>{{ $diamonddetail->amount }}</td>
                                                <td>{{ $diamonddetail->reason }}</td>
                                                <td>{{ $diamonddetail->shape }}</td>
                                                <td>{{ $diamonddetail->color }}</td>
                                                <td>{{ $diamonddetail->clarity }}</td>
                                                <td>{{ $diamonddetail->cut }}</td>
                                                <td>{{ $diamonddetail->pol }}</td>
                                                <td>{{ $diamonddetail->symm }}</td>
                                                <td>{{ $diamonddetail->floro }}</td>
                                                <td>{{ $diamonddetail->lab }}</td>
                                                <td>{{ $diamonddetail->lab_no }}</td>
                                                <td>{{ $diamonddetail->pcs }}</td>
                                                <td>{{ $diamonddetail->rate }}</td>
                                                <td>{{ $diamonddetail->mm1 }}</td>
                                                <td>{{ $diamonddetail->mm2 }}</td>
                                                <td>{{ $diamonddetail->mm3 }}</td>
                                                <td>{{ $diamonddetail->table }}</td>
                                                <td>{{ $diamonddetail->td }}</td>
                                                <td>{{ $diamonddetail->agent }}</td>
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
            $('#remaining_weight').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenweight').val(selectedDate);
            });
            $('#color').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddencolor').val(selectedDate);
            });
            $('#shape').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenshape').val(selectedDate);
            });
            $('#from_weight').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddenfromweight').val(selectedDate);
            });
            $('#to_weight').on('change', function() {
                var selectedDate = $(this).val();
                $('#hiddentoweight').val(selectedDate);
            });
        });
    </script>
@endsection
