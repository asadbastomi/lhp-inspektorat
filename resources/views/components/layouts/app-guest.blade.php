<!DOCTYPE html>
<html lang="id" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Sistem Monitoring Laporan' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="h-full flex items-center justify-center p-4 sm:p-6">

    {{ $slot }}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Global notification handler -->
    <script>
        document.addEventListener('livewire:initialized', () => {


            // Listen for Livewire flash messages
            Livewire.on('notify', (data) => {


                // Handle both direct object and array-wrapped data
                let notificationData = {};

                if (Array.isArray(data) && data.length > 0) {
                    // If data is an array, use the first element
                    notificationData = data[0] || {};
                } else if (typeof data === 'object' && data !== null) {
                    // If data is an object, use it directly
                    notificationData = data;
                }

                // Extract values with defaults
                const type = notificationData.type || 'info';
                const title = notificationData.title || (type === 'error' ? 'Error' : type === 'success' ?
                    'Success' : 'Info');
                const message = notificationData.message || (type === 'error' ? 'An error occurred' : '');
                const timer = notificationData.timer || (type === 'error' ? 5000 : 3000);

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: timer,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer);
                        toast.addEventListener('mouseleave', Swal.resumeTimer);
                    }
                });

                // For toast notifications, combine title and message for better display
                const toastTitle = title;
                const toastHtml = message ? `<div class="text-sm mt-1">${message}</div>` : '';



                const toast = Toast.fire({
                    icon: type,
                    title: toastTitle,
                    html: toastHtml
                });

                toast.then((result) => {
                    // Handle toast close if needed
                }).catch((error) => {
                    console.error('Toast error in main layout:', error);
                });
            });

            // Handle session flash messages
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Informasi',
                    text: '{{ session('info') }}',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Error Validasi',
                        text: '{{ $error }}',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 5000,
                        timerProgressBar: true
                    });
                @endforeach
            @endif
        });
    </script>
    @livewireScripts
</body>

</html>
