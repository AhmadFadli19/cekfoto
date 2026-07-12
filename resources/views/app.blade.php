<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="CatinGuard — Sistem Deteksi Dini & Triase Geospasial Risiko Rhesus & Thalasemia untuk Calon Pengantin Indonesia">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CatinGuard — Skrining Kesehatan Pranikah Indonesia</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/react@18/umd/react.production.min.js" crossorigin></script>
    <script src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js" crossorigin></script>
    <script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>
    <style>{!! file_get_contents(resource_path('css/catinguard.css')) !!}</style>
</head>
<body>
    <div id="root"></div>
    <script type="text/babel" data-type="module">
    {!! file_get_contents(resource_path('js/indonesia-map.jsx')) !!}
    {!! file_get_contents(resource_path('js/catinguard-app.jsx')) !!}
    </script>
</body>
</html>
