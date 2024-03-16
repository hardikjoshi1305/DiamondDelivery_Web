@extends('Backend.Layouts.app')
@section('content')
    <style>
        .modal-sm {
            max-width: 460px !important;
        }
    </style>
    <div class="breadcrumbs">
        <div class="col-sm-4">
            <div class="page-header float-left">
                <div class="page-title">
                    <h1>Shipment Mode</h1>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <button type="button" class="btn btn-secondary mb-1" id="mdlshow">
                                <i class="fa fa-plus mr-2"></i>Add Shipment
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Shipment</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($shipment as $shipments)
                                            <tr>
                                                <td>{{ $shipments->name }}</td>

                                                <td class="table-action">
                                                    <a href="javascript:void(0);" id="edit-agent"
                                                        data-id="{{ $shipments->id }}" class="action-icon"><i
                                                            class="fa fa-edit ml-2" style="font-size:20px;"></i></a>
                                                    <a href="javascript:void(0);" id="delete-agent"
                                                        data-id="{{ $shipments->id }}" class="action-icon"> <i
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

    <!-- Modal -->

    <div class="modal fade" id="agentModal" tabindex="-1" aria-labelledby="smallmodalLabel" style="display: none;">

        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form method="POST" id="addagent" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hidden_id" name="hidden_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="smallmodalLabel">Add Shipment</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">Shipment Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter Shipment">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveagent" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        var SITEURL = '{{ URL::to('') }}';
        $(document).ready(function() {

            $("#mdlshow").click(function() {
                $('#smallmodalLabel').html("Add Shipment");
                $('#hidden_id').val("");
                $('#name').val("");

                $('#agentModal').modal('toggle');
            });

            $('#addagent').submit(function(e) {
                e.preventDefault();
                var hidden_id = $("#hidden_id").val();
                var name = $("#name").val();
                if (name == "") {
                    $("#name").focus();
                    toastr.warning('Please Enter Shipment Name');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('add.shipment') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            hidden_id:hidden_id,
                            name: name,
                        },
                        success: (data) => {
                            if (data['success']) {
                                toastr.success(data['success']);
                                window.location.reload();
                            } else {
                                toastr.error(data['error']);
                            }
                        },
                    });
                }
            });
            $('body').on('click', '#edit-agent', function() {
                var id = $(this).data('id');
                var _token = $("input[name='_token']").val();
                $.ajax({
                    url: "{{ route('edit.shipment') }}",
                    type: 'POST',
                    data: {
                        _token: _token,
                        id: id,
                    },
                    success: function(data) {
                        $('#agentModal').modal('toggle');
                        $('#smallmodalLabel').html("Edit Agent");
                        $('#hidden_id').val(data.id);
                        $('#name').val(data.name);
                    }
                });
            });
            $('body').on('click', '#delete-agent', function() {
                var id = $(this).data("id");
                var _token = $("input[name='_token']").val();
                $.ajax({
                    url: "{{ route('delete.shipment') }}",
                    type: 'POST',
                    data: {
                        _token: _token,
                        id: id,
                    },
                    success: function(data) {
                        if (data['success']) {
                            toastr.success(data['success']);
                            window.location.reload();
                        } else {
                            toastr.error(data['error']);
                        }
                    }
                });
            });
        });
    </script>
@endsection
