
<!DOCTYPE html>
<html lang>
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width,initial-scale=1" />
<title>Leon Slotow Associates</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
  .logo{
    max-height: 20rem !important;
    width: 260px;
    height: 11rem;
  }
  .card-body{
    padding: 0;
  }
  .bg-secondary{
      color: #000;
      background-color: #7b809a;
      border-color: #7b809a;
  }
</style>
</head>

<body class="bg-gray-200">
  <main class="main-content position-relative max-height-vh-100 h-100 overflow-x-hidden">    
    <div class="container-fluid py-4">
      <div class="row">
        <div class="col-10 mx-auto">
          <div class="card mt-4">
            <div class="card-body pb-0">

              {!! $message_body !!}

              <div class="mt-3 bg-secondary" >
                <img src="https://goverify.co.za/public/logo.png" class="logo" />
                </div>
            </div>
          </div>
 
        </div>
      </div>
    </div>
  </main>

</body>
</html>
