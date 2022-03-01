@extends('main.layouts.main')
@section('content')
<div class="row justify-content-center">
    <div class="col-6 card p-4">
        <div class="d-flex justify-content-between">
            <h4 class="">Informasi Pribadi</h4>
        </div>
        <br>
        <form action="{{ route('admin.update.post', [$data->id]) }}" method="post">
            @csrf
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Nama Lengkap</label>
                <div class="col-8">
                    <input type="text" name="fullname" value="{{$data->fullname}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">NIS</label>
                <div class="col-8">
                    <input type="text" readonly value="{{$data->nisn}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Saldo</label>
                <div class="col-8">
                    <input type="text" readonly value="Rp {{number_format($data->saldo)}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">No Rekening</label>
                <div class="col-8">
                    <input type="text" readonly value="{{$data->account_number}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Cif Number</label>
                <div class="col-8">
                    <input type="text" readonly value="{{$data->cif_number}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Email</label>
                <div class="col-8">
                    <input type="text" name="email"  value="{{$data->email}}" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">No. Handphone</label>
                <div class="col-8">
                    <input type="text" name="telp"  value="{{$data->telp ?? ''}}" placeholder="Belum Menambahkan No. Handphone" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Password Baru</label>
                <div class="col-8">
                    <input type="password" name="password"  placeholder="Masukan Password Baru" class="form-control">
                </div>
            </div>
            <div class="form-group row mb-2">
                <label for="" class="label-form-col my-2 col-md-4">
                    Foto
                </label>
                <div class="col-md-8">
                    <input type="file" class="form-control" name="thumbnail"  placeholder="thumbnail" value="{{$data->thumbnail ?? ''}}">
                </div>
            </div>
            @if(isset($data))
            <div class="form-group row mb-2">
                <label for="" class="label-form-col my-2 col-md-4">
                    Current Foto
                </label>
                <div class="col-md-8">
                    <img class="img-thumbnail w-25" src="{{ url($data->thumbnail ?? '') }}">
                </div>
            </div>
            @endif
            <div class="form-group row mb-2">
                <label for="" class="label-form-col col-4 my-2">Tanggal Daftar</label>
                <div class="col-8">
                    <input type="text" readonly value="{{$data->created_at ?? date('d/m/Y')}}" class="form-control">
                </div>
            </div>
            <div class="text-end mt-4 {{ $data->verified != 1 ?? 'd-none' }}" id="submit-btn">
                <button class="btn btn-info text-light" type="submit">Simpan</button>
                <a href="{{ route('me') }}" class="btn btn-danger text-light">Batal</a>
            </div>
        </form>
    </div>
</div>

@stop
