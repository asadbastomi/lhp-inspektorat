<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-[#263238]">Dashboard Analitik</h1>
            <p class="text-gray-600 mt-1">Ringkasan data LHP secara real-time.</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="card mb-8">
        <div class="flex items-center mb-4">
            <i class="fas fa-filter text-[#0277BD] text-xl mr-3"></i>
            <h3 class="text-lg font-semibold text-[#263238]">Filter Data</h3>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <label for="temuanFilter" class="block text-sm font-medium text-gray-700 mb-1">Jenis Temuan</label>
                <select id="temuanFilter" wire:model.live="temuanFilter" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                    <option value="all">Semua Jenis Temuan</option>
                    <option value="administratif">Temuan Administratif</option>
                    <option value="material">Kerugian Material</option>
                </select>
            </div>
            <div>
                <label for="irbanFilter" class="block text-sm font-medium text-gray-700 mb-1">Irban</label>
                <select id="irbanFilter" wire:model.live="selectedIrban" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                    <option value="all">Semua Irban</option>
                    @foreach($irbans as $irban)
                        <option value="{{ $irban->id }}">{{ $irban->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="yearFilter" class="block text-sm font-medium text-gray-700 mb-1">Tahun</label>
                <select id="yearFilter" wire:model.live="selectedYear" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                    @foreach($years as $year)
                        <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="monthFilter" class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                <select id="monthFilter" wire:model.live="selectedMonth" class="w-full rounded-lg border-gray-300 shadow-sm focus:ring-[#1B5E20] focus:border-[#1B5E20]">
                    <option value="all">Semua Bulan</option>
                    @foreach(range(1, 12) as $month)
                        <option value="{{ $month }}">{{ \Carbon\Carbon::create()->month($month)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Main Content Area with Loading State -->
    <div wire:loading.class.delay="opacity-50 transition-opacity" class="transition-opacity">
        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @php
                $statCards = [
                    ['title' => 'Total Laporan', 'value' => $stats['totalLaporan'], 'icon' => 'fa-file-alt', 'color' => 'blue'],
                    ['title' => 'Jumlah Temuan', 'value' => $stats['jumlahTemuan'], 'icon' => 'fa-search', 'color' => 'yellow'],
                    ['title' => 'Total Kerugian', 'value' => 'Rp ' . number_format($stats['totalKerugian'], 0, ',', '.'), 'icon' => 'fa-money-bill-wave', 'color' => 'red'],
                    ['title' => '% Penyelesaian', 'value' => $stats['persentasePenyelesaian'] . '%', 'icon' => 'fa-check-circle', 'color' => 'green'],
                ];
            @endphp
            @foreach($statCards as $card)
            <div class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border-l-4 border-{{$card['color']}}-400">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wide">{{ $card['title'] }}</h3>
                        <p class="text-3xl font-bold text-[#263238] mt-2">{{ $card['value'] }}</p>
                    </div>
                    <div class="bg-{{$card['color']}}-100 p-3 rounded-full">
                        <i class="fas {{ $card['icon'] }} text-{{$card['color']}}-600 text-xl"></i>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Charts Section with ApexCharts -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8" 
             x-data="dashboardCharts()" 
             x-init="$nextTick(() => initCharts({{ json_encode($this->getChartData()) }}))"
             wire:ignore>
            <!-- Temuan per Irban -->
            <div class="card lg:col-span-2">
                <h3 class="text-lg font-semibold text-[#263238] mb-4">Temuan per Irban</h3>
                <div id="temuanIrbanChart" class="h-80"></div>
            </div>
            <!-- Status Penyelesaian -->
            <div class="card">
                <h3 class="text-lg font-semibold text-[#263238] mb-4">Status Penyelesaian</h3>
                <div id="statusPenyelesaianChart" class="h-80"></div>
            </div>
            <!-- Kerugian per Bulan -->
            <div class="card lg:col-span-3">
                <h3 class="text-lg font-semibold text-[#263238] mb-4">Kerugian per Bulan ({{ $selectedYear }})</h3>
                <div id="kerugianBulananChart" class="h-80"></div>
            </div>
        </div>

        <!-- Recent LHP Table -->
        <div class="card">
            <h3 class="text-lg font-semibold text-[#263238] mb-4">Laporan Terbaru</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nomor LHP</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Irban</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kerugian</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @forelse($recentLhps as $lhp)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#263238]">{{ $lhp->nomor_lhp }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $lhp->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span @class([
                                    'px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    'bg-green-100 text-[#1B5E20]' => $lhp->status_penyelesaian == 'selesai',
                                    'bg-[#FBC02D]/20 text-[#263238]' => $lhp->status_penyelesaian == 'dalam_proses',
                                    'bg-red-100 text-[#C62828]' => $lhp->status_penyelesaian == 'belum_diproses',
                                ])>
                                    {{ Str::title(str_replace('_', ' ', $lhp->status_penyelesaian)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($lhp->besaran_temuan, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr class="border-b">
                            <td colspan="4" class="px-6 py-12 text-center text-sm text-gray-500">
                                Tidak ada laporan terbaru yang cocok dengan filter Anda.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- ApexCharts CDN -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    function dashboardCharts() {
        return {
            temuanIrbanChart: null,
            statusPenyelesaianChart: null,
            kerugianBulananChart: null,

            initCharts(chartData) {
                const chartColors = {
                    primary: '#1B5E20',
                    primaryLight: '#388E3C',
                    secondary: '#FBC02D',
                    accent: '#0277BD',
                    danger: '#C62828',
                };
                
                // 1. Temuan per Irban (Horizontal Bar Chart)
                const temuanOptions = {
                    series: [{ data: chartData.temuanIrban.data }],
                    chart: { type: 'bar', height: '100%', toolbar: { show: false } },
                    plotOptions: { bar: { borderRadius: 4, horizontal: true } },
                    dataLabels: { enabled: false },
                    xaxis: { categories: chartData.temuanIrban.labels },
                    colors: [chartColors.primaryLight],
                };
                this.temuanIrbanChart = new ApexCharts(document.querySelector("#temuanIrbanChart"), temuanOptions);
                this.temuanIrbanChart.render();

                // 2. Status Penyelesaian (Radial Bar Chart)
                const statusData = chartData.statusPenyelesaian;
                const totalStatus = statusData.selesai + statusData.dalam_proses + statusData.belum_diproses;
                
                // FIX: The series data must be a simple array of numbers for radialBar.
                const statusSeriesData = totalStatus > 0 ? [
                    parseFloat(((statusData.selesai / totalStatus) * 100).toFixed(1)),
                    parseFloat(((statusData.dalam_proses / totalStatus) * 100).toFixed(1)),
                    parseFloat(((statusData.belum_diproses / totalStatus) * 100).toFixed(1))
                ] : [0, 0, 0];

                const statusOptions = {
                    series: statusSeriesData,
                    chart: { type: 'radialBar', height: '100%' },
                    plotOptions: {
                        radialBar: {
                            offsetY: 0,
                            startAngle: 0,
                            endAngle: 270,
                            hollow: { margin: 5, size: '30%', background: 'transparent' },
                            dataLabels: { name: { show: false }, value: { show: false } }
                        }
                    },
                    colors: [chartColors.primary, chartColors.secondary, chartColors.danger],
                    labels: ['Selesai', 'Dalam Proses', 'Belum Diproses'],
                    legend: { show: true, floating: true, fontSize: '14px', position: 'left', offsetX: 50, offsetY: 10, labels: { useSeriesColors: true }, markers: { size: 0 }, formatter: (seriesName, opts) => seriesName + ":  " + opts.w.globals.series[opts.seriesIndex] + "%", itemMargin: { vertical: 3 } }
                };
                this.statusPenyelesaianChart = new ApexCharts(document.querySelector("#statusPenyelesaianChart"), statusOptions);
                this.statusPenyelesaianChart.render();

                // 3. Kerugian per Bulan (Area Chart)
                const kerugianOptions = {
                    series: [{ name: 'Total Kerugian', data: chartData.kerugianBulanan }],
                    chart: { type: 'area', height: '100%', toolbar: { show: false }, zoom: { enabled: false } },
                    dataLabels: { enabled: false },
                    stroke: { curve: 'smooth', width: 2 },
                    colors: [chartColors.accent],
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.6, opacityTo: 0.05 } },
                    xaxis: { categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'] },
                    yaxis: { labels: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } },
                    tooltip: { y: { formatter: (value) => 'Rp ' + new Intl.NumberFormat('id-ID').format(value) } }
                };
                this.kerugianBulananChart = new ApexCharts(document.querySelector("#kerugianBulananChart"), kerugianOptions);
                this.kerugianBulananChart.render();

                // Listen for Livewire updates
                Livewire.on('updateCharts', (event) => {
                    this.updateAllCharts(event[0]);
                });
            },

            updateAllCharts(chartData) {
                // Update Temuan Chart
                this.temuanIrbanChart.updateOptions({
                    series: [{ data: chartData.temuanIrban.data }],
                    xaxis: { categories: chartData.temuanIrban.labels }
                });
                
                // Update Status Chart
                const statusData = chartData.statusPenyelesaian;
                const totalStatus = statusData.selesai + statusData.dalam_proses + statusData.belum_diproses;
                const seriesData = totalStatus > 0 ? [
                    parseFloat(((statusData.selesai / totalStatus) * 100).toFixed(1)),
                    parseFloat(((statusData.dalam_proses / totalStatus) * 100).toFixed(1)),
                    parseFloat(((statusData.belum_diproses / totalStatus) * 100).toFixed(1))
                ] : [0, 0, 0];
                this.statusPenyelesaianChart.updateSeries(seriesData);

                // Update Kerugian Chart
                this.kerugianBulananChart.updateSeries([{ data: chartData.kerugianBulanan }]);
            }
        }
    }
</script>
@endpush
