<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tiMovie - @yield('title', 'Website')</title>
    <link href="/bootstrap/bootstrap.min.css" rel="stylesheet">
    @stack('styles')
  </head>
  <body>
    @include('layout.partials.navbar')

      <div class="container my-2">
        @yield('content')
      </div>

      @include('layout.partials.footer')

    <script src="/bootstrap/bootstrap.bundle.min.js"></script>
    @stack('scripts')
  </body>
</html>