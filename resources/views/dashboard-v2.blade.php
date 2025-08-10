<x-layouts.admin>
    <div x-data="{
        currentFilter: {
            irban: 'all',
            bulan: 'all',
            status: 'all',
            nominalMin: 0,
            nominalMax: 1000000000
        },
        dashboardData: {
            stats: {
                totalLaporan: 248,
                jumlahTemuan: 156,
                totalKerugian: 2450000000,
                persentasePenyelesaian: 73.2
            },
            chartData: {
                temuanIrban: {
                    labels: ['Wilayah I', 'Wilayah II', 'Wilayah III'],
                    data: [45, 67, 44]
                },
                kategoriTemuan: {
                    labels: ['Administratif', 'Keuangan', 'Fisik'],
                    data: [82, 54, 20]
                },
                kerugianBulanan: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    data: [450000000, 380000000, 520000000, 320000000, 410000000, 370000000]
                },
                statusPenyelesaian: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    selesai: [8, 12, 15, 10, 14, 16],
                    proses: [5, 8, 6, 9, 7, 5],
                    belum: [3, 2, 4, 6, 3, 4]
                }
            },
            tableData: [
                { nomor: 'LHP-001/2024', tanggal: '15 Jan 2024', kategori: 'Administratif', irban: 'Wilayah I', status: 'Selesai', kerugian: 45000000 },
                { nomor: 'LHP-002/2024', tanggal: '18 Jan 2024', kategori: 'Keuangan', irban: 'Wilayah II', status: 'Dalam Proses', kerugian: 120000000 },
                { nomor: 'LHP-003/2024', tanggal: '22 Jan 2024', kategori: 'Fisik', irban: 'Wilayah III', status: 'Belum Ditindaklanjuti', kerugian: 75000000 },
                { nomor: 'LHP-004/2024', tanggal: '25 Jan 2024', kategori: 'Administratif', irban: 'Wilayah I', status: 'Selesai', kerugian: 30000000 },
                { nomor: 'LHP-005/2024', tanggal: '28 Jan 2024', kategori: 'Keuangan', irban: 'Wilayah II', status: 'Dalam Proses', kerugian: 95000000 }
            ]
        }
    }">
        <!-- Filters Section -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-pink-100 mb-6">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-filter text-blue-400 mr-2"></i>
                    Filter Data
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Irban</label>
                        <select x-model="currentFilter.irban" class="w-full bg-blue-50 border border-blue-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-transparent">
                            <option value="all">Semua Irban</option>
                            <option value="wilayah1">Wilayah I</option>
                            <option value="wilayah2">Wilayah II</option>
                            <option value="wilayah3">Wilayah III</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                        <select x-model="currentFilter.bulan" class="w-full bg-green-50 border border-green-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-300 focus:border-transparent">
                            <option value="all">Semua Bulan</option>
                            <option value="1">Januari</option>
                            <option value="2">Februari</option>
                            <option value="3">Maret</option>
                            <option value="4">April</option>
                            <option value="5">Mei</option>
                            <option value="6">Juni</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select x-model="currentFilter.status" class="w-full bg-purple-50 border border-purple-200 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300 focus:border-transparent">
                            <option value="all">Semua Status</option>
                            <option value="selesai">Selesai</option>
                            <option value="proses">Dalam Proses</option>
                            <option value="belum">Belum Ditindaklanjuti</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Range Kerugian</label>
                        <input type="range" x-model="currentFilter.nominalMax" min="0" max="1000000000" step="10000000" 
                               class="w-full h-2 bg-pink-200 rounded-lg appearance-none cursor-pointer slider">
                        <div class="text-xs text-gray-500 mt-1">
                            Max: Rp <span x-text="(currentFilter.nominalMax / 1000000).toFixed(0)">0</span> Juta
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-pink-100 to-pink-200 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-pink-600 uppercase tracking-wide">Total Laporan</h3>
                        <p class="text-3xl font-bold text-pink-800 mt-2" x-text="dashboardData.stats.totalLaporan">248</p>
                    </div>
                    <div class="bg-pink-300 p-3 rounded-full">
                        <i class="fas fa-file-alt text-pink-700 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-100 to-blue-200 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-blue-600 uppercase tracking-wide">Jumlah Temuan</h3>
                        <p class="text-3xl font-bold text-blue-800 mt-2" x-text="dashboardData.stats.jumlahTemuan">156</p>
                    </div>
                    <div class="bg-blue-300 p-3 rounded-full">
                        <i class="fas fa-search text-blue-700 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-100 to-red-200 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-red-600 uppercase tracking-wide">Total Kerugian</h3>
                        <p class="text-2xl font-bold text-red-800 mt-2">
                            Rp <span x-text="(dashboardData.stats.totalKerugian / 1000000000).toFixed(1)">2.5</span>M
                        </p>
                    </div>
                    <div class="bg-red-300 p-3 rounded-full">
                        <i class="fas fa-money-bill-wave text-red-700 text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-100 to-green-200 rounded-2xl shadow-lg p-6 hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-sm font-medium text-green-600 uppercase tracking-wide">% Penyelesaian</h3>
                        <p class="text-3xl font-bold text-green-800 mt-2">
                            <span x-text="dashboardData.stats.persentasePenyelesaian">73.2</span>%
                        </p>
                    </div>
                    <div class="bg-green-300 p-3 rounded-full">
                        <i class="fas fa-check-circle text-green-700 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Bar Chart: Temuan berdasarkan Irban -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-blue-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-blue-400 mr-2"></i>
                        Temuan berdasarkan Irban
                    </h3>
                    <div class="chart-container" style="height:300px">
                        <canvas id="irbanChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Pie Chart: Kategori Temuan -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-purple-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-chart-pie text-purple-400 mr-2"></i>
                        Berdasarkan Kategori Temuan
                    </h3>
                    <div class="chart-container" style="height:300px">
                        <canvas id="kategoriChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Line Chart: Kerugian Material per Bulan -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-green-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-chart-line text-green-400 mr-2"></i>
                        Kerugian Material per Bulan
                    </h3>
                    <div class="chart-container" style="height:300px">
                        <canvas id="kerugianChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Stacked Bar: Status Penyelesaian per Bulan -->
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-pink-100">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">
                        <i class="fas fa-chart-bar text-pink-400 mr-2"></i>
                        Status Penyelesaian per Bulan
                    </h3>
                    <div class="chart-container" style="height:300px">
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-lg border border-gray-100">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-table text-indigo-400 mr-2"></i>
                    Tabel Laporan
                </h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="bg-gradient-to-r from-indigo-50 to-purple-50">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nomor LHP</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Irban</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kerugian</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <template x-for="item in dashboardData.tableData" :key="item.nomor">
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" x-text="item.nomor"></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.tanggal"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="{
                                                  'bg-blue-100 text-blue-800': item.kategori === 'Administratif',
                                                  'bg-yellow-100 text-yellow-800': item.kategori === 'Keuangan',
                                                  'bg-purple-100 text-purple-800': item.kategori === 'Fisik'
                                              }" x-text="item.kategori"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" x-text="item.irban"></td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                                              :class="{
                                                  'bg-green-100 text-green-800': item.status === 'Selesai',
                                                  'bg-yellow-100 text-yellow-800': item.status === 'Dalam Proses',
                                                  'bg-red-100 text-red-800': item.status === 'Belum Ditindaklanjuti'
                                              }" x-text="item.status"></span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        Rp <span x-text="(item.kerugian / 1000000).toFixed(0)"></span> Juta
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dashboardData = {
                chartData: {
                    temuanIrban: {
                        labels: ['Wilayah I', 'Wilayah II', 'Wilayah III'],
                        data: [45, 67, 44]
                    },
                    kategoriTemuan: {
                        labels: ['Administratif', 'Keuangan', 'Fisik'],
                        data: [82, 54, 20]
                    },
                    kerugianBulanan: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        data: [450000000, 380000000, 520000000, 320000000, 410000000, 370000000]
                    },
                    statusPenyelesaian: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                        selesai: [8, 12, 15, 10, 14, 16],
                        proses: [5, 8, 6, 9, 7, 5],
                        belum: [3, 2, 4, 6, 3, 4]
                    }
                }
            };

            // Irban Bar Chart
            const irbanCtx = document.getElementById('irbanChart').getContext('2d');
            new Chart(irbanCtx, {
                type: 'bar',
                data: {
                    labels: dashboardData.chartData.temuanIrban.labels,
                    datasets: [{
                        label: 'Jumlah Temuan',
                        data: dashboardData.chartData.temuanIrban.data,
                        backgroundColor: '#93c5fd',
                        borderColor: '#3b82f6',
                        borderWidth: 1,
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Kategori Pie Chart
            const kategoriCtx = document.getElementById('kategoriChart').getContext('2d');
            new Chart(kategoriCtx, {
                type: 'doughnut',
                data: {
                    labels: dashboardData.chartData.kategoriTemuan.labels,
                    datasets: [{
                        data: dashboardData.chartData.kategoriTemuan.data,
                        backgroundColor: ['#d8b4fe', '#a78bfa', '#8b5cf6'],
                        hoverOffset: 4,
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });

            // Kerugian Line Chart
            const kerugianCtx = document.getElementById('kerugianChart').getContext('2d');
            new Chart(kerugianCtx, {
                type: 'line',
                data: {
                    labels: dashboardData.chartData.kerugianBulanan.labels,
                    datasets: [{
                        label: 'Kerugian (Rp)',
                        data: dashboardData.chartData.kerugianBulanan.data,
                        borderColor: '#4ade80',
                        backgroundColor: 'rgba(74, 222, 128, 0.1)',
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + (value / 1000000) + 'Jt';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Status Stacked Bar Chart
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: dashboardData.chartData.statusPenyelesaian.labels,
                    datasets: [
                        {
                            label: 'Selesai',
                            data: dashboardData.chartData.statusPenyelesaian.selesai,
                            backgroundColor: '#86efac',
                            borderRadius: 4
                        },
                        {
                            label: 'Dalam Proses',
                            data: dashboardData.chartData.statusPenyelesaian.proses,
                            backgroundColor: '#fcd34d',
                            borderRadius: 4
                        },
                        {
                            label: 'Belum Ditindaklanjuti',
                            data: dashboardData.chartData.statusPenyelesaian.belum,
                            backgroundColor: '#f87171',
                            borderRadius: 4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            grid: {
                                display: false
                            }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        });
    </script>
    @endpush
</x-layouts.admin>
