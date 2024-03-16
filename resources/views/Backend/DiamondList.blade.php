@extends('Backend.Layouts.app')
@section('content')
@if (session('success'))
    <div id="successAlert" class="alert alert-success" style="position: absolute; right: 0px; background:Green; color:black;">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div id="successAlert" class="alert alert-error" style="position: absolute; right: 0px; background:rgb(241, 13, 24); color:black;">
        {{ session('error') }}
    </div>
@endif

    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Diamond List</h1>
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
                                            <th>ShipmentDate</th>
                                            <th>ShipmentMode</th>
                                            <th>SNNO</th>
                                            <th>SELLDATE</th>
                                            <th>SOLD_BY</th>
                                            <th>CLIENT</th>
                                            <th>SHAPE</th>
                                            <th>WEIGHT</th>
                                            <th>PCS</th>
                                            <th>COLOR</th>
                                            <th>CLARITY</th>
                                            <th>CUT</th>
                                            <th>POL</th>
                                            <th>SYMN</th>
                                            <th>FLORO</th>
                                            <th>LAB</th>
                                            <th>LABNO</th>
                                            <th>MM1</th>
                                            <th>MM2</th>
                                            <th>MM3</th>
                                            <th>TABLE</th>
                                            <th>TD</th>
                                            <th>RATE</th>
                                            <th>TOTAL</th>
                                            <th>REMAININGWEIGHT</th>
                                            <th>AGENT</th>
                                            <th>ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($diamond as $diamonds)
                                        <tr>
                                            <td>{{ date('d-m-y', strtotime($diamonds->shipment_date)) }}</td>
                                            <td>{{$diamonds->shipment_mode }}</td>
                                            <td>{{$diamonds->sn_no}}</td>
                                            <td>{{$diamonds->sell_date}}</td>
                                            <td>{{$diamonds->sold_by}}</td>
                                            <td>{{$diamonds->client}}</td>
                                            <td>{{$diamonds->shape}}</td>
                                            <td>{{$diamonds->weight}}</td>
                                            <td>{{$diamonds->pcs}}</td>
                                            <td>{{$diamonds->color}}</td>
                                            <td>{{$diamonds->clarity}}</td>
                                            <td>{{$diamonds->cut}}</td>
                                            <td>{{$diamonds->pol}}</td>
                                            <td>{{$diamonds->symm}}</td>
                                            <td>{{$diamonds->floro}}</td>
                                            <td>{{$diamonds->lab}}</td>
                                            <td>{{$diamonds->lab_no}}</td>
                                            <td>{{$diamonds->mm1}}</td>
                                            <td>{{$diamonds->mm2}}</td>
                                            <td>{{$diamonds->mm3}}</td>
                                            <td>{{$diamonds->table}}</td>
                                            <td>{{$diamonds->td}}</td>
                                            <td>{{$diamonds->rate}}</td>
                                            <td>{{$diamonds->total}}</td>
                                            <td>{{$diamonds->remaining_weight}}</td>
                                            <td>{{$diamonds->agent}}</td>
                                            <td class="table-action">
                                                <a href="{{ route('edit',$diamonds->id) }}" id="edit-diamond"
                                                    data-id="{{ $diamonds->id }}" class="action-icon"><i
                                                        class="fa fa-edit ml-2" style="font-size:20px;"></i></a>
                                                <a href="{{ route('delete', $diamonds->id) }}" id="delete-diamond"
                                                    data-id="{{ $diamonds->id }}" class="action-icon"> <i
                                                        class="fa fa-trash-o ml-2" style="font-size:20px;"></i></a>
                                            </td>
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
            var alertTimeout = setTimeout(function() {
                $("#successAlert").alert('close');
            }, 2000); // 2000 milliseconds (2 seconds)
            $("#successAlert").on('closed.bs.alert', function() {
                clearTimeout(alertTimeout);
            });
        });
    </script>
@endsection
