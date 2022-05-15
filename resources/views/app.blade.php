<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>
        <link
        rel="stylesheet"
        type="text/css"
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"
      />
      <!-- Material Icons -->
      <link
        href="https://fonts.googleapis.com/icon?family=Material+Icons+Round"
        rel="stylesheet"
      />
  
      <!-- Font Awesome Icons -->
      <script
        src="https://kit.fontawesome.com/42d5adcbca.js"
        crossorigin="anonymous"
      ></script>
      <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"
      />


        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
       

        <!-- Scripts -->
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
    </head>
    <body class="g-sidenav-show " >
        @inertia
    </body>
</html>
