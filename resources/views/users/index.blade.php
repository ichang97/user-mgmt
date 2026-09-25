@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">จัดการผู้ใช้งาน</h4>
            <p class="text-muted small mb-0">ระบบบริหารจัดการรายชื่อและสถานะของผู้ใช้งานในระบบ</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            + เพิ่มผู้ใช้ใหม่
        </a>
    </div>

    <div class="card border-0 shadow-sm rounded-3 mb-4">
        <div class="card-body p-3">
            <form method="GET" action="{{ route('users.index') }}" class="row g-2 align-items-center">
                <div class="col-md-5">
                    <input type="text" name="search" class="form-control" placeholder="ค้นหาจากชื่อ หรืออีเมล..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-select">
                        <option value="">สถานะทั้งหมด</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>ใช้งานปกติ</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ถูกระงับ</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" class="btn btn-primary px-3">
                        ค้นหา
                    </button>
                    @if (request()->hasAny(['search', 'status']))
                        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                            ล้างตัวกรอง
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-3">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">ID</th>
                        <th>ชื่อ</th>
                        <th>อีเมล</th>
                        <th>สถานะ</th>
                        <th>วันที่สร้าง</th>
                        <th class="text-end pe-4">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="ps-4 text-muted">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if ($user->status === 'active')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">
                                        ใช้งานปกติ
                                    </span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2 py-1">
                                        ถูกระงับ
                                    </span>
                                @endif
                            </td>
                            <td class="text-muted small">{{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' }}</td>
                            <td class="text-end pe-4">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-outline-primary btn-sm me-1">
                                    แก้ไข
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="d-inline delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete" data-username="{{ $user->name }}">
                                            ลบ
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                ไม่พบข้อมูลผู้ใช้งาน
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="card-footer bg-white border-0 py-3 d-flex justify-content-end">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('custom-script')
<script type="module">
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            const userName = this.getAttribute('data-username');
            if (confirm(`คุณต้องการลบผู้ใช้ "${userName}" ออกจากระบบอย่างถาวรใช่หรือไม่?`)) {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush
