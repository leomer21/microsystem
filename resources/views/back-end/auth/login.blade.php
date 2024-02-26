@extends('...back-end.layouts.app')
@section('title', 'Login')
@section('content')
<style>
.panel-transparent {

        background: rgba(255,255,255, 0.5)!important;
}

</style>
        <!-- Simple login form -->
        <form method="POST" action="{{ url('/login') }}" >
        {{ csrf_field() }}
            <center><div class="panel panel-body login-form panel-transparent">
                <div class="text-center">
                    <div class="icon-object border-slate-600 text-slate-600"><i class="icon-reading"></i></div>
                    <?php 
                    if(!session('Identify')){
    
                        $fullURL=url('');
                        $domainExploded=explode("http://", $fullURL);
                        if(!isset($domainExploded[1])){
                            $domainExploded=explode("https://", $fullURL);
                        }
                        $domainExploded2=explode(".", $domainExploded[1]);
                        $finalCompanyName = strtoupper($domainExploded2[0]);

                    }else{
                        $finalCompanyName = strtoupper(session('Identify')[0]);
                    }
                    
                    ?>
                    <h5 class="content-group">Login to your account at<strong class="display-block" style="color: black">{{ $finalCompanyName }}</strong></h5>
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input name="email" type="text" @if(isset($_REQUEST['email'])) value={{$_REQUEST['email']}} @endif class="form-control" placeholder="Email">
                    <div class="form-control-feedback">
                        <i class="icon-user text-muted"></i>
                    </div>
                    @if ($errors->has('email'))
                        <span class="validation-error-label">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group has-feedback has-feedback-left">
                    <input name="password" type="password" class="form-control" placeholder="Password">
                    <div class="form-control-feedback">
                        <i class="icon-lock2 text-muted"></i>
                    </div>
                    @if ($errors->has('password'))
                        <span class="validation-error-label">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group login-options">
                <!--<div class="row">
                    <div class="col-sm-6">
                        <label class="checkbox-inline">
                            <input type="checkbox" class="styled" name="remember">
                            Remember
                        </label>
                    </div>

                    <div class="col-sm-6 text-right">
                        <a href="{{ url('/password/reset') }}">Forgot password?</a>
                    </div>
                </div>-->

				</div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-block">Sign in <i class="icon-circle-right2 position-right"></i></button>
                </div>
            </div></center>
        </form>
        <!-- /simple login form -->
@endsection
