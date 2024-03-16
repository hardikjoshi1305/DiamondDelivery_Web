@extends('Backend.Layouts.app')
@section('content')
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Edit DiamondList</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('update.diamond') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" id="hidden_id" name="hidden_id" value="{{ $diamond->id }}"
                                class="form-control">
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">ShipmentDATE</label>
                                    <input type="text" id="date" name="date" value="{{ $diamond->shipment_date }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">SHIPMENT MODE</label>
                                    <input type="text" id="shipment_mode" name="shipment_mode"
                                        value="{{ $diamond->shipment_mode }}" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">SELL DATE</label>
                                    <input type="text" id="sell_date" name="sell_date" value="{{ $diamond->sell_date }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">SOLD BY</label>
                                    <input type="text" id="sold_by" name="sold_by" value="{{ $diamond->sold_by }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <label class="form-label">CLIENT</label>
                                    <input type="text" id="client" name="client" value="{{ $diamond->client }}"
                                        class="form-control">
                                </div>
                                {{--  <div class="col-md-3">
                                    <label class="form-label">CONTACT NO</label>
                                    <input type="text" id="contact_no" name="contact_no"
                                        value="{{ $diamond->contact_no }}" class="form-control">
                                </div>  --}}
                                <div class="col-md-3">
                                    <label class="form-label">SHAPE</label>
                                    <input type="text" id="shape" name="shape" value="{{ $diamond->shape }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">WEIGHT</label>
                                    <input type="text" id="weight" name="weight" value="{{ $diamond->weight }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">COLOR</label>
                                    <input type="text" id="color" name="color" value="{{ $diamond->color }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">CLARITY</label>
                                    <input type="text" id="clarity" name="clarity" value="{{ $diamond->clarity }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">CUT</label>
                                    <input type="text" id="cut" name="cut" value="{{ $diamond->cut }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">POL</label>
                                    <input type="text" id="pol" name="pol" value="{{ $diamond->pol }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">SYMM</label>
                                    <input type="text" id="symm" name="symm" value="{{ $diamond->symm }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">FLORO</label>
                                    <input type="text" id="floro" name="floro" value="{{ $diamond->floro }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">LAB</label>
                                    <input type="text" id="lab" name="lab" value="{{ $diamond->lab }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">LAB NO</label>
                                    <input type="text" id="lab_no" name="lab_no" value="{{ $diamond->lab_no }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">PCS</label>
                                    <input type="text" id="pcs" name="pcs" value="{{ $diamond->pcs }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">RATE</label>
                                    <input type="text" id="rate" name="rate" value="{{ $diamond->rate }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">MM1</label>
                                    <input type="text" id="mm1" name="mm1" value="{{ $diamond->mm1 }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">MM2</label>
                                    <input type="text" id="mm2" name="mm2" value="{{ $diamond->mm2 }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <label class="form-label">MM3</label>
                                    <input type="text" id="mm3" name="mm3" value="{{ $diamond->mm3 }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">TABLE</label>
                                    <input type="text" id="table" name="table" value="{{ $diamond->table }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">TD</label>
                                    <input type="text" id="td" name="td" value="{{ $diamond->td }}"
                                        class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">TOTAL</label>
                                    <input type="text" id="total" name="total" value="{{ $diamond->total }}"
                                        class="form-control">
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-sm-3">
                                    <label class="form-label" for="agent">Agent Name</label>
                                    <select id="agent" name="agent" class="form-control">
                                        <option value="">Select Agent Name</option>
                                        @foreach ($agent as $agents)
                                            <option value="{{ $agents->name }}"
                                                {{ $diamond->agent == $agents->name ? 'selected' : '' }}>
                                                {{ $agents->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mt-4">
                                    <button type="submit" class="btn btn-secondary"
                                        style="margin-top: 8px;">Submit</button>
                                </div>
                            </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
@endsection
