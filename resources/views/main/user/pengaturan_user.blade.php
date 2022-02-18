@extends('main.layouts.main')
@section('content')
<div class="row justify-content-center">
    <div class="col-6 card p-4">
        <div class="d-flex justify-content-between">
            <h4 class="">Ubah Info Pribadi</h4>
            {{-- <a href="{{ route('user') }}" class="btn btn-info text-light">Kembali</a> --}}
        </div>
        <br>
            @csrf
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">Nama Lengkap</label>
                <div class="col-9">
                    <input type="text" value="{{$data->fullname}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">NIS</label>
                <div class="col-9">
                    <input type="text" readonly value="{{$data->nisn}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">Saldo</label>
                <div class="col-9">
                    <input type="text" readonly value="Rp {{number_format($data->saldo)}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">Email</label>
                <div class="col-6">
                    <input type="email" name="email" value="{{$data->email}}" class="form-control">
                </div>
                <div class="col-3 my-1 text-end">
                    <button class="btn btn-info btn-sm text-light">Send OTP</button>
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">OTP</label>
                <div class="col-9">
                    <input type="text" name="otp" placeholder="...." class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">No. Handphone</label>
                <div class="col-9">
                    <input type="text" name="telp" value="{{$data->telp ?? 'Belum Menambahkan No. Handphone'}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-3 my-2">Tanggal Daftar</label>
                <div class="col-9">
                    <input type="text" readonly value="{{$data->created_at ?? date('d/m/Y')}}" class="form-control">
                </div>
            </div>
            <div class="text-end mt-4">
                <button class="btn btn-info text-light">Simpan</button>
                <button class="btn btn-danger text-light">Batal</button>
            </div>
    </div>
</div>

@stop