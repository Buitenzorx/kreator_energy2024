@extends('layouts.app')

@section('title', 'Dashboard Kapasitas Tong Sampah')

@section('content')
    <div class="container" style="text-align: center; margin-top: 10px;">
        <h1>Dashboard Kapasitas Tong Sampah</h1>

        <div class="row">
            <!-- Tong Sampah Organik -->
            <div class="col-md-4">
                <div class="card mb-3 shadow">
                    <div class="card mb-3">
                        <div class="card-header" style="font-size: 20px; font-weight: bold; background-color: #1E201E; color: white;">
                            <h3>SAMPAH ORGANIK</h3>
                        </div>
                        <div class="card-body">
                            <p>Jarak: <span id="jarak-organik">{{ $data['tong_organik']->last()->jarak ?? 'N/A' }}</span> cm</p>
                            <div class="tong-sampah" style="height: 100px; position: relative;">
                                <div id="isi-organik" class="isi-sampah" style="height: {{ calculateHeight($data['tong_organik']->last()->jarak ?? 100) }}; background-color: {{ getColor($data['tong_organik']->last()->jarak ?? 100) }};">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tong Sampah Non-Organik -->
            <div class="col-md-4">
                <div class="card mb-3 shadow">
                    <div class="card mb-3">
                        <div class="card-header" style="font-size: 20px; font-weight: bold; background-color: #1E201E; color: white;">
                            <h3>SAMPAH ANORGANIK</h3>
                        </div>
                        <div class="card-body">
                            <p>Jarak: <span id="jarak-anorganik">{{ $data['tong_non_organik']->last()->jarak ?? 'N/A' }}</span> cm</p>
                            <div class="tong-sampah" style="height: 100px; position: relative;">
                                <div id="isi-anorganik" class="isi-sampah" style="height: {{ calculateHeight($data['tong_non_organik']->last()->jarak ?? 100) }}; background-color: {{ getColor($data['tong_non_organik']->last()->jarak ?? 100) }};">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tong Sampah Logam -->
            <div class="col-md-4">
                <div class="card mb-3 shadow">
                    <div class="card mb-3">
                        <div class="card-header" style="font-size: 20px; font-weight: bold; background-color: #1E201E; color: white;">
                            <h3>SAMPAH LOGAM</h3>
                        </div>
                        <div class="card-body">
                            <p>Jarak: <span id="jarak-logam">{{ $data['tong_logam']->last()->jarak ?? 'N/A' }}</span> cm</p>
                            <div class="tong-sampah" style="height: 100px; position: relative;">
                                <div id="isi-logam" class="isi-sampah" style="height: {{ calculateHeight($data['tong_logam']->last()->jarak ?? 100) }}; background-color: {{ getColor($data['tong_logam']->last()->jarak ?? 100) }};">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card untuk Grafik -->
        <div class="card mt-5">
            <div class="card-header" style="font-size: 20px; font-weight: bold; background-color: #1E201E; color: white;">
                <h3>Grafik Kapasitas Tong Sampah</h3>
            </div>
            <div class="card-body">
                <div class="row mt-5">
                    <div class="col-md-12">
                        <canvas id="jarakChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const ctx = document.getElementById('jarakChart').getContext('2d');
        let jarakChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($timestamps),
                datasets: [
                    {
                        label: 'Sampah Organik',
                        data: @json($data['tong_organik']->pluck('jarak')),
                        borderColor: 'green',
                        fill: false,
                    },
                    {
                        label: 'Sampah Anorganik',
                        data: @json($data['tong_non_organik']->pluck('jarak')),
                        borderColor: 'yellow',
                        fill: false,
                    },
                    {
                        label: 'Sampah Logam',
                        data: @json($data['tong_logam']->pluck('jarak')),
                        borderColor: 'red',
                        fill: false,
                    }
                ]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: true,
                    },
                },
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Waktu'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Jarak (cm)'
                        },
                        beginAtZero: true
                    }
                }
            }
        });

        function fetchData() {
            $.get('/api/tong-sampah-data', function(data) {
                // Update jarak pada setiap card
                $('#jarak-organik').text(data.organik[data.organik.length - 1] ?? 'N/A');
                $('#jarak-anorganik').text(data.anorganik[data.anorganik.length - 1] ?? 'N/A');
                $('#jarak-logam').text(data.logam[data.logam.length - 1] ?? 'N/A');
                
                // Update isi-sampah height and color
                $('#isi-organik').css({
                    'height': calculateHeight(data.organik[data.organik.length - 1] ?? 100),
                    'background-color': getColor(data.organik[data.organik.length - 1] ?? 100)
                });
                $('#isi-anorganik').css({
                    'height': calculateHeight(data.anorganik[data.anorganik.length - 1] ?? 100),
                    'background-color': getColor(data.anorganik[data.anorganik.length - 1] ?? 100)
                });
                $('#isi-logam').css({
                    'height': calculateHeight(data.logam[data.logam.length - 1] ?? 100),
                    'background-color': getColor(data.logam[data.logam.length - 1] ?? 100)
                });
                
                // Update grafik dengan data terbaru
                jarakChart.data.labels = data.timestamps;
                jarakChart.data.datasets[0].data = data.organik;
                jarakChart.data.datasets[1].data = data.anorganik;
                jarakChart.data.datasets[2].data = data.logam;
                jarakChart.update();
            }).fail(function() {
                console.error("Terjadi kesalahan saat mengambil data.");
            });
        }

        function calculateHeight(jarak) {
            const maxHeight = 100;
            return Math.max(0, Math.min(maxHeight, maxHeight - jarak * (maxHeight / 100))) + 'px';
        }

        function getColor(jarak) {
            if (jarak <= 20) return 'red';
            else if (jarak <= 50) return 'yellow';
            else if (jarak <= 100) return 'green';
            return 'lightgray';
        }

        setInterval(fetchData, 5000);
    </script>
@endsection

@php
    function getColor($jarak)
    {
        if ($jarak <= 20) {
            return 'red';
        } elseif ($jarak <= 50) {
            return 'yellow';
        } elseif ($jarak <= 100) {
            return 'green';
        }
        return 'lightgray';
    }

    function calculateHeight($jarak)
    {
        $maxHeight = 100;
        $height = max(0, min($maxHeight, $maxHeight - $jarak * ($maxHeight / 100)));
        return $height . 'px';
    }
@endphp

<style>
    .tong-sampah {
        border: 2px solid #000;
        border-radius: 10px;
        margin: 5px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .isi-sampah {
        position: absolute;
        bottom: 0;
        width: 100%;
        transition: height 0.5s ease;
    }

    .row {
        margin-top: 20px;
    }
</style>
