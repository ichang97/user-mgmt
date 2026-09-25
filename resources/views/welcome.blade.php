@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h1 class="display-5 fw-bold text-dark mb-3">
                ระบบบริหารจัดการผู้ใช้งาน
            </h1>
            <div class="d-flex justify-content-center gap-3">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
                        เข้าสู่ระบบ
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary btn-lg px-4">
                            ลงทะเบียนบัญชีใหม่
                        </a>
                    @endif
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
                        ไปยังแดชบอร์ด
                    </a>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-lg px-4">
                        จัดการผู้ใช้งาน
                    </a>
                @endguest
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
