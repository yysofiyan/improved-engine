@extends('layouts.master-admin')

@section('title', 'Dashboard')
@section('content')


<div class="row">
    <div class="col-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Masukan Kode PIN Pendaftaran Online</h4>
                <div class="card-description">
                    <p>Petunjuk :</p>
                    <ol class="list">
                        <li>Silahkan Masukan Kode PIN Pendaftaran Online untuk dirubah ke Offline
                        </li>
                    </ol>
                </div>
                <form action="{{ url('admin/pos_migrasi') }}" method="post" 
                    id="formImport">
                    @csrf
                    
                    <div class="row">
                        <div class="form-group col-lg-12 @error('pin') has-danger @enderror">
                            <label>Kode PIN Pendaftaran</label>
                            <input type="pin" name="pin" class="form-control @error('pin') is-invalid @enderror" placeholder="Masukkan Pin Pendaftaran">
                            @error('pin')
                            <label class="error mt-2 text-danger">{{ $message }}</label>
                            @enderror
                        </div>
                    </div>
                    <div class="text-left">
                        <button type="submit" class="btn btn-primary" id="btnImport">Proses</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-stylesheet')
@endpush

@push('page-script')

@endpush
