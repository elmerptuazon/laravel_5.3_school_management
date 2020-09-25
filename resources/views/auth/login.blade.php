@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="box-body text-center">
                <!-- <div class="card-header">{{ __('Login') }}</div> -->

                <div class="login-box">

                    <div class="login-logo">
                        <div class="col-xs-12">
                            <img src="{{asset('/uploads/profile/loginimage.png')}}" class="img-circle" alt="school-Logo" style="height: 125px; width: 125px;">
                        </div>
                        <a href="#" ><b style="font-size: 30px">Demo</b><br /> <small style="font-size: 22px">Life Academy International</small></a>
                    </div>
                    <div class="login-box-body" style="border: 1px solid whitesmoke; margin-bottom: 1px;">
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf

                            <div class="form-group has-feedback">
                                

                                
                                    <input id="user" type="text" placeholder="Username" class="form-control{{ $errors->has('user') ? ' is-invalid' : '' }}" name="user" value="{{ old('user') }}" required autofocus>
                                    <span class="fa fa-user form-control-feedback"></span>
                                    @if ($errors->has('user'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('user') }}</strong>
                                        </span>
                                    @endif
                                
                            </div>

                            <div class="form-group has-feedback">
                                    <input id="password" type="password" placeholder="Password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>
                                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                                    @if ($errors->has('password'))
                                        <span class="invalid-feedback">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                    @endif
                            </div>

                            <div class="form-group has-feedback">
                                <div class="col-offset-4">
                                    <div class="pull-left">
                                        <label>
                                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                                        {{ __('Login') }}
                                    </button>
                                </div>
                            </div>

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
