@extends('layouts.app')

@push('styles')
    <style>
        .form-label {
            font-weight: 600;
            color: #191c1e;
            margin-bottom: 4px;
            display: block;
            font-size: 14px;
        }

        .required-star {
            color: #ef4444;
            margin-left: 2px;
            font-weight: 700;
        }

        .standard-input {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            transition: all 0.2s;
        }

        .standard-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .standard-select {
            width: 100%;
            border: 1px solid #CBD5E1;
            border-radius: 0.375rem;
            padding: 8px 12px;
            font-size: 14px;
            outline: none;
            background-color: white;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2364748B%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E");
            background-repeat: no-repeat;
            background-position: right 12px top 50%;
            background-size: 10px auto;
        }

        .standard-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
        }

        .form-error {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 4px;
        }

        .helper-text {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 4px;
        }
    </style>
@endpush

@section('content')
    <!-- Breadcrumb -->
    <nav aria-label="Breadcrumb" class="flex text-sm text-secondary mb-4 font-body-md">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li><a class="hover:text-primary transition-colors" href="{{ route('dashboard') }}">Dashboard</a></li>
            <li><span class="material-symbols-outlined text-sm mx-1">chevron_right</span><a
                    class="hover:text-primary transition-colors" href="{{ route('admin.users.index') }}">Manajemen Pengguna</a>
            </li>
            <li><span class="material-symbols-outlined text-sm mx-1">chevron_right</span><span
                    class="text-on-surface">Tambah Pengguna</span></li>
        </ol>
    </nav>

    <h1 class="font-headline-lg text-headline-lg text-on-surface mb-6">Tambah Pengguna Baru</h1>

    <div class="bg-surface-container-lowest rounded-lg shadow-sm border border-outline-variant overflow-hidden mb-8 max-w-2xl">
        <div class="px-6 py-4 bg-primary-container">
            <h2 class="text-white font-headline-md text-headline-md m-0">Form Data Pengguna</h2>
        </div>
        <div class="p-6">
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="form-label" for="name">Nama Lengkap <span class="required-star">*</span></label>
                        <input class="standard-input @error('name') border-red-500 @enderror" id="name" name="name"
                            placeholder="Masukkan nama lengkap" type="text" value="{{ old('name') }}">
                        @error('name')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="email">Email <span class="required-star">*</span></label>
                        <input class="standard-input @error('email') border-red-500 @enderror" id="email" name="email"
                            placeholder="nama@situbondokab.go.id" type="email" value="{{ old('email') }}">
                        @error('email')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="role">Role <span class="required-star">*</span></label>
                        <select class="standard-select @error('role') border-red-500 @enderror" id="role" name="role">
                            <option disabled {{ old('role') ? '' : 'selected' }} value="">-- Pilih Role --</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User OPD</option>
                        </select>
                        @error('role')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password">Password <span class="required-star">*</span></label>
                        <input class="standard-input @error('password') border-red-500 @enderror" id="password"
                            name="password" placeholder="Minimal 8 karakter" type="password">
                        @error('password')
                            <p class="form-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="form-label" for="password_confirmation">Konfirmasi Password <span class="required-star">*</span></label>
                        <input class="standard-input" id="password_confirmation" name="password_confirmation"
                            placeholder="Ulangi password" type="password">
                    </div>
                </div>

                <div class="flex items-center gap-3 mt-8 pt-6 border-t border-outline-variant">
                    <button type="submit" class="flex items-center gap-2 bg-primary hover:bg-primary-container text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-colors shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">save</span> Simpan Pengguna
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-lg text-sm font-medium text-secondary hover:bg-surface-container-low transition-colors">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
