<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8" />
        <title>Log In |Diamond Manegement</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
        <meta content="Coderthemes" name="author" />

        <link rel="shortcut icon" href="{{ asset('public\Backend\images\Diamond.png') }}">

        <script src="{{asset('public/Backend/js/hyper-config.js')}}"></script>

        <link href="{{asset('public/Backend/css/app-saas.min.css')}}" rel="stylesheet" type="text/css" id="app-style" />
        <link rel="stylesheet" href="{{ asset('public/Backend/vendors/font-awesome/css/font-awesome.min.css') }}">

        <link href="{{asset('public/Backend/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
    </head>

    <body class="authentication-bg">
        <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xxl-4 col-lg-5">
                        <div class="card">

                            <!-- Logo -->
                            <div class="card-header pt-2 text-center">
                                <a href="">

                                    <span><img src="{{ asset('public/Backend/images/Diamond.png') }}" alt="logo" height="100px;" width="100px;"></span>
                                </a>
                            </div>

                            <div class="card-body p-4  pt-0">

                                <div class="text-center w-75 m-auto">
                                    <h4 class="text-dark-50 text-center pb-0 fw-bold">Sign In</h4>
                                </div>
                                    @if($message = Session::get('success'))
                                        <div class="alert alert-info">
                                            {{ $message }}
                                        </div>
                                    @endif
                                <form action="{{ route('login.custom') }}" method="post">
							     	@csrf
                                    <div class="mb-3">
                                        <label for="emailaddress" class="form-label">Email address</label>
                                        <input class="form-control" type="email" name="email" id="emailaddress" required="" placeholder="Enter your email">
										@if($errors->has('email'))
							              <span class="text-danger">{{ $errors->first('email') }}</span>
			                 			@endif
									</div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group input-group-merge">
                                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password">
                                            <div class="input-group-text" data-password="false"><i class="fa fa-eye-slash"></i>
                                                <span class=""></span>
                                            </div>
											@if($errors->has('password'))
							                  <span class="text-danger">{{ $errors->first('password') }}</span>
						                   @endif
                                        </div>
                                    </div>

                                    <div class="mb-3 mb-0 text-center">
                                        <button class="btn btn-dark" type="submit"> Log In </button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Vendor js -->
        <script src="{{asset('public/Backend/js/vendor.min.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('public/Backend/js/app.min.js')}}"></script>

    </body>
</html>
