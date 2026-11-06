<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Google Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<!-- Tailwind CSS (via CDN for instant zero-config rendering + Vite) -->
<script src="https://cdn.tailwindcss.com"></script>
<script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: {
                    sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    mono: ['"JetBrains Mono"', 'monospace'],
                },
                colors: {
                    primary: {
                        50: '#eef2ff',
                        100: '#e0e7ff',
                        500: '#6366f1',
                        600: '#4f46e5',
                        700: '#4338ca',
                    }
                }
            }
        }
    }
</script>

@if(file_exists(public_path('build/manifest.json')))
    @vite(['resources/css/app.css'])
@endif

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

