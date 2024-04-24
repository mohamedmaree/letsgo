@extends('layouts.master')
@section('content')


                        <form action="{{route('createContact')}}" method="POST">
                            @csrf
                            <input type="text" name="name" value="{{old('name')}}" class="form-control wow fadeInUp" data-wow-delay=".2s" placeholder="الاسم " required>
                                @if($errors->has('name'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('name')}}
                                </div>
                                @endif
                            <input type="email" name="email" value="{{old('email')}}" class="form-control wow fadeInUp" data-wow-delay=".3s" placeholder="البريد الالكترونى " required>
                                @if($errors->has('email'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('email')}}
                                </div>
                                @endif
                            <textarea type="text" name="message" class="form-control wow fadeInUp" data-wow-delay=".4s" placeholder="الرسالة " required> {{old('message')}}</textarea>
                                @if($errors->has('message'))
                                <div class="alert alert-danger" role="alert">
                                {{$errors->first('message')}}
                                </div>
                                @endif
                            <button class="btn btn-green btn-animate wow fadeInUp" data-wow-delay=".5s" type="submit"> <span> ارسال </span></button>
                        </form>


@endsection