@component('mail::message')

{!! $message_body !!}
<br>
<style>
  .logo{
   width:100%;
   
  }
  .bg-secondary{
      color: #000;
      background-color: #7b809a;
      border-color: #7b809a;
  }
</style>
<div class="mt-3 bg-secondary" >
       <img src="{{ asset('logo.png') }}" alt="Slotow Logo" class="logo">
 </div>


<br>

@endcomponent