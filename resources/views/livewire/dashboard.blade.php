<div x-data="{}" x-init="$nextTick(() => {
    const cards = document.querySelectorAll('.stats-card');
    cards.forEach((card, index) => {
        card.style.setProperty('--delay', `${index * 150}ms`);
        card.classList.add('animate-slide-up');
    });
})">
    <!-- Hero Section -->
    <div
        class="relative bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800 rounded-3xl shadow-2xl overflow-hidden mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-2/3 text-white mb-8 lg:mb-0">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-chart-line text-white mr-2"></i>
                        <span class="text-sm font-medium">Dasbor Inspektorat</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Selamat Datang,
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            {{ Auth::user()->name }}
                        </span>
                    </h1>
                    <p class="text-xl text-blue-100 leading-relaxed">
                        Pantau dan kelola Laporan Hasil Pemeriksaan (LHP) dengan mudah melalui dasbor yang komprehensif
                        ini.
                    </p>
                </div>
                <div class="lg:w-1/3 flex justify-center">
                    <div class="relative">
                        <div
                            class="w-48 h-48 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <i class="fas fa-chart-pie text-white text-6xl"></i>
                        </div>
                        <div
                            class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-star text-yellow-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex items-center mb-6">
            <div
                class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                <i class="fas fa-filter text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Filter Data</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Sesuaikan tampilan data sesuai kebutuhan</p>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Temuan</label>
                <select wire:model.live="temuanFilter"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="all">Semua Jenis Temuan</option>
                    <option value="administratif">Temuan Administratif</option>
                    <option value="material">Kerugian Material</option>
                </select>
            </div>

            @if (Auth::user()->role === 'admin')
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Irban</label>
                    <select wire:model.live="selectedIrban"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        <option value="">Semua Irban</option>
                        @foreach ($irbans as $irban)
                            <option value="{{ $irban->id }}">{{ $irban->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bulan</label>
                <select wire:model.live="selectedMonth"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    <option value="all">Semua Bulan</option>
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}">
                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tahun</label>
                <select wire:model.live="selectedYear"
                    class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                    @foreach (range(date('Y'), 2020) as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div
            class="stats-card bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Total LHP</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['totalLaporan']) }}</p>
                    <p class="text-blue-200 text-xs mt-2">Laporan keseluruhan</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-file-alt text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="stats-card bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">LHP dengan Temuan</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['jumlahTemuan']) }}</p>
                    <p class="text-green-200 text-xs mt-2">Ada temuan audit</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-search text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="stats-card bg-gradient-to-br from-red-500 to-red-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total Kerugian</p>
                    <p class="text-2xl font-bold mt-1">Rp {{ number_format($stats['totalKerugian'], 0, ',', '.') }}</p>
                    <p class="text-red-200 text-xs mt-2">Kerugian material</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
        </div>

        <div
            class="stats-card bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Selesai</p>
                    <p class="text-3xl font-bold mt-1">{{ number_format($stats['penyelesaianSelesai']) }}</p>
                    <p class="text-purple-200 text-xs mt-2">Tindak lanjut selesai</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Temuan per Irban Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Temuan per Irban</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Distribusi temuan berdasarkan unit</p>
                </div>
                <div
                    class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-bar text-white"></i>
                </div>
            </div>
            <div id="temuanIrbanChart" class="h-80"></div>
        </div>

        <!-- Kerugian Bulanan Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Kerugian Bulanan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Tren kerugian sepanjang tahun</p>
                </div>
                <div
                    class="w-10 h-10 bg-gradient-to-br from-teal-500 to-blue-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line text-white"></i>
                </div>
            </div>
            <div id="kerugianBulananChart" class="h-80"></div>
        </div>
    </div>

    <!-- Recent LHP Table -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">LHP Terbaru</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">5 laporan terakhir yang dibuat</p>
                </div>
                <a href="{{ route('lhps') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-xl transition-colors duration-200">
                    <i class="fas fa-eye mr-2"></i>
                    Lihat Semua
                </a>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Nomor LHP
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Irban
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Status
                        </th>
                        <th
                            class="px-6 py-4 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Temuan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentLhps as $lhp)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-blue-100 dark:bg-blue-900/50 rounded-lg flex items-center justify-center mr-3">
                                        <i class="fas fa-file-alt text-blue-600 dark:text-blue-400 text-sm"></i>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $lhp->nomor_lhp }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->tanggal_lhp->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->user->name }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'belum_ditindaklanjuti' => [
                                            'bg' => 'bg-red-100 text-red-800',
                                            'text' => 'Belum Ditindaklanjuti',
                                        ],
                                        'dalam_proses' => [
                                            'bg' => 'bg-yellow-100 text-yellow-800',
                                            'text' => 'Dalam Proses',
                                        ],
                                        'sesuai' => ['bg' => 'bg-green-100 text-green-800', 'text' => 'Sesuai'],
                                    ];
                                    $config = $statusConfig[$lhp->status_penyelesaian] ?? [
                                        'bg' => 'bg-gray-100 text-gray-800',
                                        'text' => 'Unknown',
                                    ];
                                @endphp
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $config['bg'] }}">
                                    {{ $config['text'] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $lhp->temuans->count() }} temuan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                <i class="fas fa-inbox text-4xl mb-4"></i>
                                <p>Belum ada LHP yang dibuat</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
    <style>
        .stats-card {
            opacity: 0;
            transform: translateY(20px);
        }

        .animate-slide-up {
            animation: slideUp 0.6s ease-out forwards;
            animation-delay: var(--delay, 0ms);
        }

        @keyframes slideUp {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @this.on('chartDataUpdated', (data) => {
                renderCharts(data);
            });

            // Initial chart render
            renderCharts(@json($this->getChartData()));

            function renderCharts(data) {
                // Temuan per Irban Chart
                const temuanIrbanOptions = {
                    series: [{
                        name: 'Jumlah Temuan',
                        data: data.temuanIrbanData.map(item => item.temuan_count)
                    }],
                    chart: {
                        type: 'bar',
                        height: 320,
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#3B82F6'],
                    plotOptions: {
                        bar: {
                            borderRadius: 8,
                            horizontal: false,
                            columnWidth: '60%',
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        show: true,
                        width: 2,
                        colors: ['transparent']
                    },
                    xaxis: {
                        categories: data.temuanIrbanData.map(item => item.name),
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Jumlah Temuan'
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return val + " temuan"
                            }
                        }
                    },
                    grid: {
                        strokeDashArray: 3
                    }
                };

                if (document.getElementById('temuanIrbanChart')) {
                    const temuanChart = new ApexCharts(document.querySelector("#temuanIrbanChart"),
                        temuanIrbanOptions);
                    temuanChart.render();
                }

                // Kerugian Bulanan Chart
                const kerugianBulananOptions = {
                    series: [{
                        name: 'Kerugian (Rp)',
                        data: Object.values(data.kerugianBulananData)
                    }],
                    chart: {
                        type: 'area',
                        height: 320,
                        toolbar: {
                            show: false
                        }
                    },
                    colors: ['#10B981'],
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.7,
                            opacityTo: 0.1,
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        curve: 'smooth',
                        width: 3
                    },
                    xaxis: {
                        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt',
                            'Nov', 'Des'
                        ],
                        labels: {
                            style: {
                                fontSize: '12px'
                            }
                        }
                    },
                    yaxis: {
                        title: {
                            text: 'Kerugian (Rp)'
                        },
                        labels: {
                            formatter: function(val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    },
                    tooltip: {
                        y: {
                            formatter: function(val) {
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(val);
                            }
                        }
                    },
                    grid: {
                        strokeDashArray: 3
                    }
                };

                if (document.getElementById('kerugianBulananChart')) {
                    const kerugianChart = new ApexCharts(document.querySelector("#kerugianBulananChart"),
                        kerugianBulananOptions);
                    kerugianChart.render();
                }
            }
        });
    </script>
@endpush
