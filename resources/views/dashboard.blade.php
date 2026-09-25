@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold mb-0">แดชบอร์ดภาพรวมระบบ</h4>
                <div>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-primary btn-sm me-2">
                        จัดการผู้ใช้งาน
                    </a>
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        + เพิ่มผู้ใช้ใหม่
                    </a>
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <span class="text-muted text-uppercase small fw-semibold">ผู้ใช้งานทั้งหมด</span>
                            <h2 class="display-6 fw-bold text-dark mt-2 mb-0">{{ number_format($totalUsers) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <span class="text-success text-uppercase small fw-semibold">สถานะใช้งานปกติ</span>
                            <h2 class="display-6 fw-bold text-success mt-2 mb-0">{{ number_format($activeUsers) }}</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <span class="text-danger text-uppercase small fw-semibold">ถูกระงับการใช้งาน</span>
                            <h2 class="display-6 fw-bold text-danger mt-2 mb-0">{{ number_format($inactiveUsers) }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title fw-bold mb-0">จัดการผู้ใช้งาน</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('users.index') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-light text-dark h-100">
                                    <h6 class="fw-bold mb-1">จัดการผู้ใช้งานระบบ</h6>
                                    <p class="text-muted small mb-0">ดูรายชื่อผู้ใช้งานทั้งหมด ค้นหา แก้ไขข้อมูล กำหนดสถานะ หรือลบผู้ใช้</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                <div class="p-3 border rounded-3 bg-light text-dark h-100">
                                    <h6 class="fw-bold mb-1">แก้ไขโปรไฟล์ส่วนตัว</h6>
                                    <p class="text-muted small mb-0">อัปเดตข้อมูลบัญชีผู้ใช้ของคุณ เช่น ชื่อ นามสกุล และเปลี่ยนรหัสผ่าน</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
