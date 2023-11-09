@extends('layouts.app')
@section('content')
<div id="page" class="page">




    <x-header/>


      <div class="inner-page-wrapper">

        <section id="service-details-page" class="bg-fixed wide-100 page-hero-section division">
          <div class="container"> 


            <!-- PAGE HERO TEXT -->
            <div class="row"> 
              <div class="col-md-10 col-xl-8 offset-md-1 offset-xl-2">
                <div class="hero-txt text-center white-color">

                  <!-- Title -->
                  <h2 class="h2-lg">Our Products And Services</h2>

                  

                </div>
              </div>  
            </div>    <!-- END PAGE HERO TEXT -->




          </div>     <!-- End container --> 
        </section>  
        <section id="about-10" class="bg-color-01 wide-80 about-section division">
          <div class="container">
            <div class="row">


              <!-- TEXT BLOCK --> 
              <div class="col-lg-8">
                <div class="txt-block pr-30">

                  <!-- Title -->  
                  <h4 class="h4-xs txt-color-01">Our Products and Services</h4>

                  <!-- Text -->
                  <p class="txt-color-05">{{ env('APP_NAME') }} will provide instruction for beauty for students registered within our programs. We
believe to generate revenues from both tuition, sale of classroom materials and beauty products and
house call beauty services. We also believe that we will receive a notable number of applicants as
individuals try to acquire new opportunities in light of the current economic trends within the
beauty industry in South Africa central to Gauteng.
                  </p>

                  <!-- Text -->
                  <p class="txt-color-05">This is especially true as the demand for individuals seeking beauty and cosmetology licenses has
increased greatly over the past five year given the current economic climate within South Africa and
other Africa Countries.  
                  </p>

                  <!-- Title -->  
                  <h4 class="h4-xs txt-color-01 mt-20">Our service offerings are listed below.</h4>

                  <!-- Text -->
                  <p class="txt-color-05">{{ env('APP_NAME') }} offers Further Qualification and Training Certification 80646 in Beauty Technology (NQF
Level 4).
                  </p>
                  <p>One Year Beauty Technology Fees: <b>R45 000</b></p>

                  <!-- List --> 
                  <ul class="simple-list txt-color-05 mb-20">

                    <li class="list-item">
                      <p>5 000 registration fee, balance of R40 000 is payable monthly for 10 months.
                      </p>
                    </li>

                    <li class="list-item">
                      <p>This fee, if full year, covers 2 uniforms, towels, starter kits of each module, graduation fee
certificate of completion and qualification</p>
                    </li>

                    <li class="list-item">
                      <p>Duration: 1 year
                      </p>
                    </li>
                    <li class="list-item">
                      <p>January - December
                      </p>
                    </li>
                    <li class="list-item">
                      <p>Monday - Friday
                      </p>
                    </li>
                    <li class="list-item">
                      <p>9am - 4pm</p>
                    </li>


                  </ul> <!-- End Text List -->  

                  <!-- Inner Image -->
                  <div class="inner-img">
                    <img class="img-fluid" src="{{ asset('images/pinkmeup/img-9.jpg') }}" alt="about-image"
                    style="max-height: 600px" />
                  </div>

                  <!-- Text -->
                  <p class="txt-color-05">The purpose of the qualification is to offer a dynamic, professional development and knowledge to
those looking to grow and work in the beauty industry. Manicurist usually grow from being a
manicurist and do a full beauty course. We offer short courses to cater for everyone who is willing to
learn a skill and grow in the industry and start their own businesses. Customer service is taught as it
run a long way, self-discipline is our priority as we work with skin as well as person to person in this
industry.
                  </p>

                  <p class="txt-color-05">The qualification is awarded at the end or completion of each module covers and to people that
have demonstrated skills of their work. After the qualification, the graduate can then perform work
in a professional manner at any beauty salon or spa.
                  </p>

                  <!-- Title -->  
                  <h4 class="h4-xs txt-color-01 mt-20">Entry Requirements</h4>
                  <ul class="simple-list txt-color-05">
                    
                    <li class="list-item">
                      <p>Grade 11</p>
                    </li>

                    <li class="list-item">
                      <p>ID or Passport</p>
                    </li>

                    <li class="list-item">
                      <p>We also offer short-courses selective from our full qualification
                       </p>
                    </li>

                  </ul>             

                </div>
              </div>  <!-- END TEXT BLOCK -->


              <!-- SIDEBAR -->
              <aside id="sidebar" class="col-lg-4">


  
                <div class="sidebar-table sidebar-div mb-50">


                  <!-- Text -->
                  <p class="txt-color-05">{{ env('APP_NAME') }} also offers a wide range of beauty and cosmetology services for any students and clients
rates. 
                  </p>

                  <!-- Table -->
                  <table class="table txt-color-05">
                    <tbody>
                        <tr>
                            <td>Manicure</td>
                            <td> </td>
                            <td class="text-right">Pedicure</td>
                        </tr>
                        <tr>
                            <td>Gelish</td>
                            <td> </td>
                            <td class="text-right">Gelish + Pedi</td>
                        </tr>
                        <tr class="last-tr">
                            <td>Ombre Tips</td>
                            <td></td>
                            <td class="text-right">Acrylic Tips + Gelish</td>
                         </tr>

                       <tr>
                            <td>Ombre Overlays</td>
                            <td> </td>
                            <td class="text-right">Overlays + Gelish</td>
                        </tr>
                      <tr>
                            <td> Soak + Re-app</td>
                            <td> </td>
                            <td class="text-right">Soak Off Only</td>
                        </tr>
                      <tr>
                            <td>Art From</td>
                            <td> </td>
                            <td class="text-right">Browmax + Tint</td>
                        </tr>
                      <tr>
                            <td>Thread + Tint</td>
                            <td> </td>
                            <td class="text-right">Underarm</td>
                        </tr>
                      <tr>
                            <td>Brazilian</td>
                            <td> </td>
                            <td class="text-right">Bikini</td>
                        </tr>
                      <tr>
                            <td>Hollywood</td>
                            <td> </td>
                            <td class="text-right">Half Leg</td>
                        </tr>
                      <tr>
                            <td>Full Leg</td>
                            <td> </td>
                            <td class="text-right">Classic Lashes</td>
                        </tr>

                      <tr>
                            <td>Classic Lashes</td>
                            <td> </td>
                            <td class="text-right">Micro blabbing</td>
                        </tr>
                      <tr>
                            <td>Touch Up</td>
                            <td> </td>
                            <td class="text-right">Full Body Massage</td>
                        </tr>
                      
                      </tbody>
                  </table>

                </div>  <!-- END SIDEBAR TABLE -->


                <!-- IMAGE WIDGET -->           
                <div class="image-widget sidebar-div mb-50">


                  <!-- Link -->
                  <a href="#"><img class="img-fluid" src="{{ asset('images/pinkmeup/img-5.jpg') }}" alt="image-widget" /></a> 
                                                    
                </div>


                <!-- TEXT WIDGET -->              
                <div id="text-widget" class="sidebar-div">
                      
                  <!-- Title -->
                  <h5 class="h5-sm txt-color-01">We also offer the following:</h5>
                  
                  <ul class="simple-list txt-color-05">
                      
                      <li class="list-item">
                        <p>House/office calls.
                        </p>
                      </li>

                      <li class="list-item">
                        <p>Group bookings.
                         </p>
                      </li>

                      <li class="list-item">
                        <p> Birthday Pampering.
                        </p>
                      </li>

                      <li class="list-item">
                        <p>  Bridal Party Pampering.
                        </p>
                      </li>

                    </ul>                       
                  
 

 
                                      
                </div>


              </aside>   <!-- END SIDEBAR -->
                  

            </div>  <!-- End row -->
          </div>     <!-- End container -->
        </section>  <!-- END ABOUT-10 -->



      </div>  <!-- END INNER PAGE WRAPPER -->




    </div>  <!-- END PAGE CONTENT -->
@endsection