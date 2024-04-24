@extends('layouts.master')

@section('content')
    <div class="home-content">
        <div class="container">
            <div class="basic-head wow fadeInUp">
                <h4>    إعادة تعيين كلمة المرور   </h4>
            </div>

            <div class="new-order-wrapper login-form wow fadeInUp">
                <form action="{{ route('password.email') }}" method="post">
                        @csrf
                    <div class="field"> 
                        <input type="text" class="form-control" placeholder="رقم الهاتف" name="phone" value="{{ old('phone') }}" required>
                    </div>
                      @if ($errors->any())
                      <div class="alert alert-danger">
                          <ul>
                              @foreach ($errors->all() as $error)
                                  <li>{{ $error }}</li>
                              @endforeach
                          </ul>
                      </div>
                      @endif
                    <button class="btn btn-green btn-animate" type="submit"> <span>  ارسال كلمة المرور     </span></button>
                </form>
            </div>
        </div>
    </div>
@endsection
