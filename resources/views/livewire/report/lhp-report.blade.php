<div>
    <!-- Hero Header -->
    <div
        class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 rounded-3xl shadow-2xl overflow-hidden mb-8">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/50 to-transparent"></div>
        <div class="relative px-8 py-12 md:px-12 md:py-16">
            <div class="flex flex-col lg:flex-row items-center justify-between">
                <div class="lg:w-2/3 text-white mb-8 lg:mb-0">
                    <div class="inline-flex items-center bg-white/20 backdrop-blur-sm rounded-full px-4 py-2 mb-4">
                        <i class="fas fa-chart-pie text-white mr-2"></i>
                        <span class="text-sm font-medium">Sistem Pelaporan</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        Laporan
                        <span class="bg-gradient-to-r from-yellow-300 to-orange-300 bg-clip-text text-transparent">
                            LHP
                        </span>
                    </h1>
                    <p class="text-xl text-indigo-100 leading-relaxed">
                        Buat dan unduh laporan Laporan Hasil Pemeriksaan (LHP) dengan filter yang dapat disesuaikan.
                    </p>
                </div>
                <div class="lg:w-1/3 flex justify-center">
                    <div class="relative">
                        <div
                            class="w-48 h-48 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center">
                            <i class="fas fa-file-pdf text-white text-6xl"></i>
                        </div>
                        <div
                            class="absolute -top-4 -right-4 w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <i class="fas fa-download text-yellow-800"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Form -->
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div
            class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800">
            <div class="flex items-center">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-filter text-white"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900 dark:text-white">Filter Laporan</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Sesuaikan parameter laporan yang akan digenerate
                    </p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <form wire:submit.prevent="generateReport">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @if (auth()->user()->role === 'admin')
                        <!-- Filter by Irban -->
                        <div class="space-y-2">
                            <label for="irban" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-user-tie mr-2 text-blue-500"></i>Irban
                            </label>
                            <select id="irban" wire:model="selectedIrban"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                                <option value="">Semua Irban</option>
                                @foreach ($irbans as $irban)
                                    <option value="{{ $irban->id }}">{{ $irban->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <!-- Filter by Jenis Pengawasan -->
                    <div class="space-y-2">
                        <label for="jenis_pengawasan"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-search mr-2 text-green-500"></i>Jenis Pengawasan
                        </label>
                        <select id="jenis_pengawasan" wire:model="jenisPengawasan"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Jenis</option>
                            @foreach (\App\Models\Temuan::$jenisPengawasanOptions as $option)
                                <option value="{{ $option }}">{{ $option }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter by Status -->
                    <div class="space-y-2">
                        <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-flag mr-2 text-purple-500"></i>Status Penyelesaian
                        </label>
                        <select id="status" wire:model="statusPenyelesaian"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Status</option>
                            <option value="belum_ditindaklanjuti">Belum Ditindaklanjuti</option>
                            <option value="dalam_proses">Dalam Proses</option>
                            <option value="sesuai">Sesuai</option>
                        </select>
                    </div>

                    <!-- Filter by Month -->
                    <div class="space-y-2">
                        <label for="month" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-calendar-alt mr-2 text-orange-500"></i>Bulan
                        </label>
                        <select id="month" wire:model="month"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Bulan</option>
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ $i }}">
                                    {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>

                    <!-- Filter by Year -->
                    <div class="space-y-2">
                        <label for="year" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <i class="fas fa-calendar mr-2 text-red-500"></i>Tahun
                        </label>
                        <select id="year" wire:model="year"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                            <option value="">Semua Tahun</option>
                            @foreach (range(date('Y'), 2020) as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Date Range Section -->
                <div
                    class="mt-8 p-6 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-2xl border border-blue-200 dark:border-blue-800">
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-week text-white text-sm"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Rentang Tanggal</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pilih periode laporan khusus (opsional)
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label for="startDate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Mulai</label>
                            <input type="date" id="startDate" wire:model="startDate"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                        <div class="space-y-2">
                            <label for="endDate"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Akhir</label>
                            <input type="date" id="endDate" wire:model="endDate"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex justify-center">
                    <button type="submit"
                        class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 text-white font-bold rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-file-pdf mr-3"></i>
                        Generate Laporan PDF
                        <i class="fas fa-arrow-right ml-3"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-download text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Format PDF</h3>
                    <p class="text-green-100 text-sm">Unduh dalam format PDF berkualitas tinggi</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-filter text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Filter Fleksibel</h3>
                    <p class="text-blue-100 text-sm">Sesuaikan laporan dengan berbagai filter</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mr-4">
                    <i class="fas fa-chart-bar text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Data Lengkap</h3>
                    <p class="text-purple-100 text-sm">Termasuk temuan, rekomendasi, dan tindak lanjut</p>
                </div>
            </div>
        </div>
    </div>
</div>
