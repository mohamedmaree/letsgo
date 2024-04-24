@extends('layouts.master')

@section('content')
    <div class="home-content">
        <div class="container">
            <div class="basic-head wow fadeInUp">
                <h4>    تغيير كلمة المرور   </h4>
            </div>

            <div class="new-order-wrapper login-form wow fadeInUp">
                <form method="POST" action="{{ route('password.createReset') }}">
                        @csrf
                      @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                      @endif    
                    <div class="field"> 
                        <input type="text" class="form-control" placeholder="رقم الهاتف" name="phone" value="{{ old('phone') }}" required autofocus>
                    </div>
                    <div class="field"> 
                        <input type="text" class="form-control" placeholder="كود التفعيل" name="code" value="{{ old('code') }}" required >
                    </div>
                    <div class="field"> 
                        <input type="password" class="form-control" placeholder="كلمة المرور" name="password" required>
                    </div>
                    <div class="field"> 
                        <input type="password" class="form-control" placeholder="تأكيد كلمة المرور" name="password_confirmation" required>
                    </div>

                    <button class="btn btn-green btn-animate" type="submit"> <span>  ارسال كلمة المرور     </span></button>
                </form>
            </div>
        </div>
    </div>
@endsection
