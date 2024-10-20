@extends('layouts.app')

@section('title', 'Riwayat Data Tong Sampah')

@section('content')
    <h1>Riwayat Data Tong Sampah</h1>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Jarak (cm)</th>
                <th>Tegangan (V)</th>
                <th>Arus (A)</th>
                <th>Daya (W)</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
                <tr>
                    <td>{{ $item->kategori }}</td>
                    <td>{{ $item->jarak }}</td>
                    <td>{{ $item->tegangan }}</td>
                    <td>{{ $item->arus }}</td>
                    <td>{{ $item->daya }}</td>
                    <td>{{ $item->created_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
