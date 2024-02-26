@extends('......layouts.app')
@section('title', 'Forget Password')
@section('content')
<!--<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Reset Password</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/reset') }}">
                        {{ csrf_field() }}

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ $email or old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation">

                                @if ($errors->has('password_confirmation'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-btn fa-refresh"></i> Reset Password
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

                        <input type="hidden" name="token" value="{{ $token }}">

						<div class="panel panel-body login-form panel-transparent">
							<div class="text-center">
								<div class="icon-object border-warning text-warning"><i class="icon-spinner11"></i></div>
								<h5 class="content-group">Password recovery <small class="display-block" style="color: #ffffff">We'll send you instructions in email</small></h5>
							</div>

							<div class="form-group has-feedback has-feedback-left">
								<input type="email" class="form-control" value="{{ $email or old('email') }}">
								<div class="form-control-feedback">
									<i class="icon-mail5 text-muted"></i>
								</div>
                                @if ($errors->has('email'))
                                    <span class="validation-error-label">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
                                @if ($errors->has('password'))
                                    <span class="validation-error-label">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
							</div>
							<div class="form-group has-feedback has-feedback-left">
								<input type="password" class="form-control">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
                                @if ($errors->has('password_confirmation'))
                                    <span class="validation-error-label">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                @endif
							</div>

                            <div class="form-group">
                            	<button type="submit" class="btn bg-blue btn-block">Reset password <i class="icon-arrow-right14 position-right"></i></button>
                            </div>

							<div class="text-center">
                                <a href="{{ url('/login') }}">Back to login?</a>
                            </div>
						</div>
					</form>
					<!-- /password recovery -->
@endsection
