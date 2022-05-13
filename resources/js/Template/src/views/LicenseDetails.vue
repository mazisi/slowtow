<template>
  <div class="container-fluid">
    <div
      class="page-header min-height-300 border-radius-xl mt-4"
      style="
        background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      "
    >
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
        <div class="col-auto">
          <div class="avatar avatar-xl position-relative">
            <img
              src="@/assets/img/resturant.png"
              alt="profile_image"
              class="shadow-sm w-100 border-radius-lg"
            />
          </div>
        </div>
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">Test License</h5>
            <p class="mb-0 font-weight-normal text-sm">GAU-0000009</p>
          </div>
        </div>
        <div
          class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0"
        >
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header">
                <h6 class="mb-0">License Details</h6>
              </div>
              <div class="p-3 card-body">
                <ul class="list-group">
                 <li class="px-0 border-0 list-group-item">
                    <vmd-switch
                      id="flexSwitchCheckDefault"
                      class="ps-0 ms-0"
                      name="flexSwitchCheckDefault"
                      label-class="mb-0 text-body text-truncate w-80"
                      checked
                      >ACTIVE LICENCE</vmd-switch
                    >
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <vmd-input
                    id=""
                    type="text"
                    label="TRADING NAME"
                    name="trading_name"
                    value="Test License"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <vmd-input
                    id=""
                    type="text"
                    label="COMPANY *"
                    name="company"
                    value="Test Company"
                    />
                  </li>

                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="LICENCE TYPE *"
                    name=""
                    value="Resturant Liquor Licence"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="LICENCE NUMBER *"
                    name=""
                    value="GAU-0000009"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="OLD LICENCE NUMBER"
                    name=""
                    value="GAU-0000078"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <p>ADDRESS *</p>
                    <vmd-textarea
                    id=""
                    type="text"
                    label="Address"
                    name=""
                    value="GAU-0000078"
                  />
                  </li>
                </ul>
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="col-12 col-md-6 col-xl-4 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header">
                <h6 class="mb-0">License Details</h6>
              </div>
              <div class="p-3 card-body">
                
                <ul class="list-group">
                  <li class="px-0 border-0 list-group-item">
                    <vmd-switch
                      id="flexSwitchCheckDefault"
                      class="ps-0 ms-0"
                      name="flexSwitchCheckDefault"
                      label-class="mb-0 text-body text-truncate w-80"
                      checked
                      >MUST RENEW</vmd-switch
                    >
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <vmd-input
                    id=""
                    type="date"
                    label="LICENCE DATE *"
                    name="trading_name"
                    value="Test License"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <vmd-input
                    id=""
                    type="date"
                    label="LICENCE EXPIRY DATE"
                    name="company"
                    value="Test Company"
                    />
                  </li>

                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="FILE NUMBER"
                    name=""
                    value="Resturant Liquor Licence"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="ACCOUNT NUMBER"
                    name=""
                    value="GAU-0000009"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="CONSULTANT NAME"
                    name=""
                    value="GAU-0000078"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <label for="exampleFormControlSelect2">PROVINCE *</label>
                        <select class="form-control" id="exampleFormControlSelect2">
                        <option>Gauteng</option>
                        <option>Mpumalanga</option>
                        <option>Limpompo</option>
                        <option>North West</option>
                        <option>Free State</option>
                        </select>
                  </li>
                </ul>
                
              </div>
            </div>
            <hr class="vertical dark" />
          </div>
          <div class="mt-4 col-12 col-xl-4 mt-xl-0">
            <div class="card card-plain h-100">
              <div class="p-3 pb-0 card-header">
                <h6 class="mb-0">Notes</h6>
              </div>
              <div class="p-3 card-body">

                 

            <form class="d-flex justify-content-center align-items-center mb-4">
              <div class="form-outline flex-fill">
                <input type="text" id="form2" class="form-control" />
                <label class="form-label" for="form2">New task...</label>
              </div>
              <button type="submit" class="btn btn-info ms-2">Add</button>
            </form>


            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
              <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel"
                aria-labelledby="ex1-tab-1">
                <ul class="list-group mb-0">
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." checked />
                    <s>Cras justo odio</s>
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." checked />
                    <s>Dapibus ac facilisis in</s>
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Morbi leo risus
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Porta ac consectetur ac
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-0 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Vestibulum at eros
                  </li>
                </ul>
              </div>
              <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                <ul class="list-group mb-0">
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Morbi leo risus
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Porta ac consectetur ac
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-0 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Vestibulum at eros
                  </li>
                </ul>
              </div>
              <div class="tab-pane fade" id="ex1-tabs-3" role="tabpanel" aria-labelledby="ex1-tab-3">
                <ul class="list-group mb-0">
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." checked />
                    <s>Cras justo odio</s>
                  </li>
                  <li class="list-group-item d-flex align-items-center border-0 mb-2 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." checked />
                    <s>Dapibus ac facilisis in</s>
                  </li>
                </ul>
              </div>
            </div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        </div>
        <div class="row mt-4">
          <div class="col-12">
            <div class="mb-5 ps-3">
              <h6 class="mb-1">Documents</h6>
            </div>
            <div class="row">
              <default-project-card
                title="Modern"
                :image="img1"
                label="ORIGINAL LICENCE"
                description="As Uber works through a huge amount of internal
                management turmoil."
                :authors="[
                  {
                    image: team1,
                    name: 'Elena Morison',
                  },
                  {
                    image: team2,
                    name: 'Ryan Milly',
                  },
                  {
                    image: team3,
                    name: 'Nick Daniel',
                  },
                  {
                    image: team4,
                    name: 'Peterson',
                  },
                ]"
                :action="{
                  color: 'success',
                  label: 'View Document',
                }"
              />

              <default-project-card
                title="Scandinavian"
                :image="img2"
                label="DUPLICATE ORIGINAL LICENCE"
                description="Music is something that every person has his or her own
                      specific opinion about."
                :authors="[
                  {
                    image: team3,
                    name: 'Nick Daniel',
                  },
                  {
                    image: team4,
                    name: 'Peterson',
                  },
                  {
                    image: team1,
                    name: 'Elena Morison',
                  },
                  {
                    image: team2,
                    name: 'Ryan Milly',
                  },
                ]"
                :action="{
                  color: 'success',
                  label: 'View Document',
                }"
              />

              <default-project-card
                title="Minimalist"
                :image="img3"
                label="LICENCE DELIVERED"
                description="Different people have different taste, and various types
                      of music."
                :authors="[
                  {
                    image: team4,
                    name: 'Peterson',
                  },
                  {
                    image: team3,
                    name: 'Nick Daniel',
                  },
                  {
                    image: team1,
                    name: 'Elena Morison',
                  },
                  {
                    image: team2,
                    name: 'Ryan Milly',
                  },
                ]"
                :action="{
                  color: 'success',
                  label: 'View Document',
                }"
              />
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import VmdInput from "@/components/VmdInput.vue";
import VmdTextarea from "@/components/VmdTextarea.vue";
import VmdSwitch from "@/components/VmdSwitch.vue";
import sophie from "@/assets/img/kal-visuals-square.jpg";
import marie from "@/assets/img/marie.jpg";
import ivana from "@/assets/img/ivana-square.jpg";
import peterson from "@/assets/img/team-4.jpg";
import nick from "@/assets/img/team-3.jpg";
import img1 from "@/assets/img/pdf-adobe.png";
import img2 from "@/assets/img/pdf-adobe.png";
import img3 from "@/assets/img/pdf-adobe.png";
import team1 from "@/assets/img/team-1.jpg";
import team2 from "@/assets/img/team-2.jpg";
import team3 from "@/assets/img/team-3.jpg";
import team4 from "@/assets/img/team-4.jpg";

import setNavPills from "@/assets/js/nav-pills.js";
import setTooltip from "@/assets/js/tooltip.js";

export default {
  name: "profile-overview",
  data() {
    return {
      showMenu: false,
      sophie,
      marie,
      ivana,
      peterson,
      nick,
      img1,
      team1,
      team2,
      team3,
      team4,
      img2,
      img3,
    };
  },
  components: {
    DefaultProjectCard,
    VmdInput,
    VmdTextarea,
    VmdSwitch,
  },

  mounted() {
    this.$store.state.isAbsolute = true;
    setNavPills();
    setTooltip();
  },
  beforeUnmount() {
    this.$store.state.isAbsolute = false;
  },
};
</script>
