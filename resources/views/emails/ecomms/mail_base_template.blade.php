
<div style="color: #000;">{!! $template !!}</div>


<br>
<style>
  .logo-slotow {
    /*max-height: 10rem !important;*/
    /*width: 300px;*/
    width: 100% !important;
    /*height: 12rem;*/
      
  }
 
  
 .container {
  flex-direction: row | row-reverse | column | column-reverse;
}

.box-container {
            display: flex;
            justify-content: center;
            width: 100%;
            align-items: center;
            margin: 20px;
        }
.box {
            width: 100%;
            color: #000;
            font-size: 12px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 1rem;
        }
  .name{width: 30%  margin-right: 10px;}   
  .phone{width: 10% }   
  .email{width: 15%;}   
  .address{width: 45%  margin-right: 10px;}  

  @media only screen and (max-width: 768px) {
    .full-width-on-mobile {
      width: 100% !important;
      margin-right: 0; /* Remove margin-right for these elements */
    }
  }
        
</style>
<div class="bg-secondary" >
       <img src="{{ asset('public/logo.jpg') }}" alt="Leon Slotow Associates Liquor Licence Specialists 011 887 2595 info@slotow.co.za 1st Floor, 21 Scott Street,Waverley, Johannesburg, Gauteng, 2090" class="logo-slotow">
<div class="box-container">
 <div class="full-width-on-mobile box name" style="">Leon Slotow Associates Liquor Licence Specialists </div>
<div class="full-width-on-mobile box phone" >  011 887 2595</div>
 <div class="full-width-on-mobile box email">info@slotow.co.za</div>
 <div class="full-width-on-mobile box address">1st Floor, 21 Scott Street,
Waverley, Johannesburg,
Gauteng, 2090</div>
</div>
 </div>