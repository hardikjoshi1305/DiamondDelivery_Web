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
                    <h1>User</h1>
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
                                <i class="fa fa-plus mr-2"></i>Add User
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="bootstrap-data-table-export" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>User Name</th>
                                            <th>Mobile No</th>
                                            <th>Password</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($user as $agents)
                                            <tr>
                                                <td>{{ $agents->name }}</td>
                                                <td>{{ $agents->mob }}</td>
                                                <td>{{ $agents->pass_txt }}</td>
                                                <td class="table-action">
                                                    <a href="javascript:void(0);" id="edit-user"
                                                        data-id="{{ $agents->id }}" class="action-icon"><i
                                                            class="fa fa-edit ml-2" style="font-size:20px;"></i></a>
                                                    <a href="javascript:void(0);" id="delete-user"
                                                        data-id="{{ $agents->id }}" class="action-icon"> <i
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

    <div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="smallmodalLabel" style="display: none;">

        <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
                <form method="POST" id="adduser" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="hidden_id" name="hidden_id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="smallmodalLabel">Add User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label" for="name">User Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Mobile No</label>
                            <input type="text" class="form-control" id="mob" name="mob"
                                placeholder="Mobile Number">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="name">Password</label>
                            <input type="text" class="form-control" id="password" name="password"
                                placeholder="password">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveuser" class="btn btn-primary">Save</button>
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
                $('#smallmodalLabel').html("Add User");
                $('#hidden_id').val("");
                $('#name').val("");
                $('#mob').val("");
                $('#password').val("");

                $('#userModal').modal('toggle');
            });

            $('#adduser').submit(function(e) {
                e.preventDefault();
                var hidden_id = $("#hidden_id").val();
                var name = $("#name").val();
                var mob = $("#mob").val();
                var password = $("#password").val();
                if (name == "") {
                    $("#name").focus();
                    toastr.warning('Please Enter User Name');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('add.user') }}",
                        data: {
                            _token: '{{ csrf_token() }}',
                            hidden_id:hidden_id,
                            name: name,
                            mob: mob,
                            password: password
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
            $('body').on('click', '#edit-user', function() {
                var id = $(this).data('id');
                var _token = $("input[name='_token']").val();
                $.ajax({
                    url: "{{ route('edit.user') }}",
                    type: 'POST',
                    data: {
                        _token: _token,
                        id: id,
                    },
                    success: function(data) {
                        $('#userModal').modal('toggle');
                        $('#smallmodalLabel').html("Edit User");
                        $('#hidden_id').val(data.id);
                        $('#name').val(data.name);
                        $('#mob').val(data.mob);
                        $('#password').val(data.pass_txt);
                    }
                });
            });
            $('body').on('click', '#delete-user', function() {
                var id = $(this).data("id");
                var _token = $("input[name='_token']").val();
                $.ajax({
                    url: "{{ route('delete.user') }}",
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
