@extends('auth.layouts.master')

@section('content')
    <div class="card">
        <div class="p-4 pt-6 text-center">
            <h1 class="mb-2 fs-26">ورود به پنل</h1>
            @if ($errors->any())
                @php
                    flash()->error('اطلاعات وارد شده صحیح نمی باشد !');
                @endphp
                <div class="alert alert-danger ">
                    <span>اطلاعات وارد شده صحیح نمیباشد.</span>
                </div>
            @endif
        </div>
        <form class="card-body pt-3" id="login" action="{{ route('login') }}" name="login" method="POST">
            @csrf
            <div class="form-group">
                <label for="mobile" class="form-label">تلفن همراه:</label>
                <input id="mobile" name="mobile" class="form-control" placeholder="شماره همراه خود را وارد کنید"
                    type="text">
            </div>
            <div class="form-group">
                <label for="password" class="form-label">کلمه عبور:</label>
                <input id="password" name="password" class="form-control" placeholder="کلمه عبور خود را وارد کنید"
                    type="password">
            </div>
            <div class="submit">
                <button class="btn btn-primary btn-block">ورود</button>
            </div>
        </form>
    </div>
@endsection
