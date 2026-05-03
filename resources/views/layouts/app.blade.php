<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'Krisna Net Billing') }}</title>

    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Tailwind CDN with Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        'jobie-primary': '#2563eb',
                        'jobie-bg': '#F9F9FC',
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        /* Jobie menu bridge trick */
        .menu-bridge {
            position: relative;
            background-color: #F9F9FC;
            border-top-left-radius: 9999px;
            border-bottom-left-radius: 9999px;
            color: #2563eb;
            font-weight: 600;
        }
        .menu-bridge::before {
            content: "";
            position: absolute;
            right: 0;
            top: -24px;
            width: 24px;
            height: 24px;
            background-color: transparent;
            border-bottom-right-radius: 20px;
            box-shadow: 0 10px 0 0 #F9F9FC;
            pointer-events: none;
        }
        .menu-bridge::after {
            content: "";
            position: absolute;
            right: 0;
            bottom: -24px;
            width: 24px;
            height: 24px;
            background-color: transparent;
            border-top-right-radius: 20px;
            box-shadow: 0 -10px 0 0 #F9F9FC;
            pointer-events: none;
        }
    </style>
</head>
<body class="min-h-screen bg-jobie-bg text-slate-900 antialiased font-sans">
    @yield('body')

    @stack('scripts')
</body>
</html>