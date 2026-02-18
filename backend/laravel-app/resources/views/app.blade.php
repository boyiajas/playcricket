<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CricBook Pro</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @php
        $user = Auth::check() ? Auth::user()->load('roles', 'permissions', 'departments') : null;

        if ($user && $user->organisation_id) {
            $tenantOrganisation = DB::connection('tenant')
                ->table('organisations')
                ->where('id', $user->organisation_id)
                ->first();

            $user->organisation = $tenantOrganisation;
        }
    @endphp

    <script>
        window.Laravel = {
            csrfToken: '{{ csrf_token() }}',
            user: {!! json_encode($user) !!}
        };

        window.onerror = function(message, source, lineno, colno, error) {
            const appDiv = document.getElementById('app');
            if (appDiv) {
                appDiv.innerHTML += '<div style="color: red; padding: 20px; background: #ffebee; border: 1px solid red; margin-top: 20px;">' +
                    '<h3>JavaScript Error:</h3>' +
                    '<p><strong>Message:</strong> ' + message + '</p>' +
                    '<p><strong>Source:</strong> ' + source + ':' + lineno + '</p>' +
                    '<p><strong>Error Object:</strong> ' + (error ? error.stack : 'N/A') + '</p>' +
                    '</div>';
            }
        };
    </script>
    @vite('src/main.js')
</head>
<body class="antialiased">
    <div id="app">
        <div style="display: flex; justify-content: center; align-items: center; height: 100vh; color: #6b7280;">
            Loading application... (If this persists, check console for errors)
        </div>
    </div>
</body>
</html>
