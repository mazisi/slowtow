<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>
      <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700"
      />
      <!-- Material Icons -->
      <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
  
      <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
       
        <script src="{{ mix('js/app.js') }}" defer></script>
        @inertiaHead
        <style>
            .half-circle-spinner, .half-circle-spinner * {
                  box-sizing: border-box;
                }
            
                .half-circle-spinner {
                  width: 20px;
                  height: 20px;
                  border-radius: 100%;
                  position: relative;
                }
            
                .half-circle-spinner .circle {
                  content: "";
                  position: absolute;
                  width: 100%;
                  height: 100%;
                  border-radius: 100%;
                  border: calc(60px / 10) solid transparent;
                }
            
                .half-circle-spinner .circle.circle-1 {
                  border-top-color: #fff;
                  animation: half-circle-spinner-animation 1s infinite;
                }
            
                .half-circle-spinner .circle.circle-2 {
                  border-bottom-color: #fff;
                  animation: half-circle-spinner-animation 1s infinite alternate;
                }
            
                @keyframes half-circle-spinner-animation {
                  0% {
                    transform: rotate(0deg);
            
                  }
                  100%{
                    transform: rotate(360deg);
                  }
                }
                </style>
    </head>
    <body class="g-sidenav-show " >
         @routes
        @inertia
       
    </body>
</html>
