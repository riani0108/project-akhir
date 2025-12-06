@extends('be.master')
@section('sidebar')
@include('be.sidebar')
@endsection

@section('navbar')
@include('be.navbar')
@endsection

@section('content')
<main class="content-wrapper">
    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <div class="row mb-5">
                            <div class="container">
                                <h2 class="fw-bold">Edit Data Antenna</h2>
                            </div>

                            <form action="{{ route('data-antenna.update', $data->id) }}" method="POST" id="frmdata">
                                @csrf
                                @method('PUT')

                                {{-- Jenis Antenna --}}
                                <div class="form-group">
                                    <label class="form-label">Jenis Antenna</label>
                                    <input class="form-control"
                                        type="text"
                                        name="jenis_antenna"
                                        value="{{ old('jenis_antenna', $data->jenis_antenna) }}"
                                        required>
                                </div>

                                {{-- Pilih Tower --}}
                                <div class="form-group mt-3">
                                    <label class="form-label">Pilih Tower</label>
                                    <select name="id_nama_tower" id="id_nama_tower" class="form-control" required>
                                        <option value="">Pilih Tower</option>

                                        @foreach ($data_tower as $tower)
                                        <option
                                            value="{{ old('id_nama_tower', $tower->id) }}"
                                            data-detail="{{ $tower->detail_lokasi }}"
                                            data-lat="{{ $tower->latitude }}"
                                            data-lng="{{ $tower->longtitude }}"
                                            {{ $tower->id == $data->id_nama_tower ? 'selected' : '' }}>
                                            {{ $tower->nama_tower }} ({{ $tower->alamat_tower }})
                                        </option>
                                        @endforeach

                                    </select>
                                </div>

                                {{-- Detail Lokasi --}}
                                <div class="form-group mt-3">
                                    <label class="form-label">Detail Lokasi</label>
                                    <input class="form-control"
                                        type="text"
                                        id="detail_lokasi"
                                        name="detail_lokasi"
                                        value="{{ old('detail_lokasi', $data->detail_lokasi) }}"
                                        required>
                                </div>

                                {{-- Latitude --}}
                                <div class="form-group mt-3">
                                    <label class="form-label">Latitude</label>
                                    <input class="form-control"
                                        type="text"
                                        id="latitude"
                                        name="latitude"
                                        value="{{ old('latitude', $data->latitude) }}"
                                        required>
                                </div>

                                {{-- Longitude --}}
                                <div class="form-group mt-3">
                                    <label class="form-label">Longitude</label>
                                    <input class="form-control"
                                        type="text"
                                        id="longtitude"
                                        name="longtitude"
                                        value="{{ old('longtitude', $data->longtitude) }}"
                                        required>
                                </div>

                                {{-- Button --}}
                                <div class="text-end mt-4">
                                    <a href="{{ route('data-antenna.index') }}" class="btn btn-secondary">
                                        Cancel
                                    </a>
                                    <button type="submit" class="btn btn-save">
                                        Save Data Antenna
                                    </button>
                                </div>

                            </form>
                        </div>
                    </div>


                    {{-- Script auto-fill tower --}}
                    <script>
                        document.getElementById('id_nama_tower').addEventListener('change', function() {
                            let selected = this.options[this.selectedIndex];

                            document.getElementById('detail_lokasi').value = selected.dataset.detail ?? '';
                            document.getElementById('latitude').value = selected.dataset.lat ?? '';
                            document.getElementById('longtitude').value = selected.dataset.lng ?? '';
                        });
                    </script>


                    @if(session('error'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: '{{ session("error") }}',
                                confirmButtonColor: '#d33'
                            });
                        });
                    </script>
                    @endif

                    @if(session('success'))
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: '{{ session("success") }}',
                                confirmButtonColor: 'green'
                            });
                        });
                    </script>
                    @endif

                    @endsection