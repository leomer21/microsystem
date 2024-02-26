@extends('......layouts.app')
@section('title', 'Forget Password')
<!-- Main Content -->
@section('content')
<!--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/email') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>-->
<style>
.panel-transparent {

        background: rgba(255,255,255, 0.5)!important;
}
</style>
<!-- Password recovery -->
					<form method="POST" action="{{ url('/password/email') }}">
					{{ csrf_field() }}

						<div class="panel panel-body login-form panel-transparent">
							<div class="text-center">
								<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
								<h5 class="content-group">Password recovery <small class="display-block" style="color: #ffffff">We'll send you instructions in email</small></h5>
							</div>

							<div class="form-group has-feedback">
								<input type="email" class="form-control" value="{{ old('email') }}">
								<div class="form-control-feedback">
									<i class="icon-mail5 text-muted"></i>
								</div>
                                @if ($errors->has('email'))
                                    <span class="validation-error-label">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
                            <div class="form-group">
                            	<button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
                            </div>
                            <div class="text-center">
                             @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                             @endif
                            </div>
							<div class="text-center">
                                <a href="{{ url('/login') }}">Back to login?</a>
                            </div>
						</div>
					</form>
					<!-- /password recovery -->
@endsection
