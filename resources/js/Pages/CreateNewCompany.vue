<script>
import Layout from "../Shared/Layout.vue";
import DefaultProjectCard from "./components/DefaultProjectCard.vue";
import VmdInput from "@/components/VmdInput.vue";
import VmdTextarea from "@/components/VmdTextarea.vue";
import VmdSwitch from "@/components/VmdSwitch.vue";
import setNavPills from "@/assets/js/nav-pills.js";
import setTooltip from "@/assets/js/tooltip.js";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
export default {
  name: "profile-overview",
  data() {
    return {
      showMenu: false,
    };
  },
  components: {
    DefaultProjectCard,
    VmdInput,
    VmdTextarea,
    VmdSwitch,
    Layout,
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

const form = useForm({
    email: '',
    password: '',
    remember: false
});

const submit = () => {
    form.post(route('company.submit'), {
        onFinish: () => form.reset('password'),
    });
};
</script>


<template>
<Layout>
<div class="container-fluid">
    <div
      class="page-header min-height-300 border-radius-xl mt-4"
      style="
        background-image: url('https://images.unsplash.com/photo-1531512073830-ba890ca4eba2?ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&ixlib=rb-1.2.1&auto=format&fit=crop&w=1920&q=80');
      ">
      <span class="mask bg-gradient-success opacity-6"></span>
    </div>
    <div class="card card-body mx-3 mx-md-4 mt-n6">
      <div class="row gx-4">
       
        <div class="col-auto my-auto">
          <div class="h-100">
            <h5 class="mb-1">New Company</h5>
          </div>
        </div>
        <div
          class="mx-auto mt-3 col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0"
        >
          
        </div>
      </div>
      <div class="row">
        <div class="mt-3 row">
          <div class="col-12 col-md-12 col-xl-12 position-relative">
            <div class="card card-plain h-100">
              <div class="p-3 card-body">
              <form @submit.prevent="submit">
                <ul class="list-group">
                 <li class="px-0 border-0 list-group-item">
                    <vmd-switch
                      id="flexSwitchCheckDefault"
                      class="ps-0 ms-0 col-6"
                      name="flexSwitchCheckDefault"
                      label-class="mb-0 text-body text-truncate w-80"
                      checked
                      >Active Company</vmd-switch
                    >
                  </li>
                 <li class="px-0 border-0 list-group-item">
  <div class="input-group input-group-outline null is-filled">
  <label class="form-label">Company Name *</label>
  <input id="" type="text" class="form-control form-control-default" name="company_name" placeholder="" isrequired="true">
  </div>
</li>
                  <li class="px-0 border-0 list-group-item"><div class="input-group input-group-outline is-filled">
                  <label class="form-label">Company Type </label>
                  <select class="form-control form-control-default" name="company_name" isrequired="true">
                  <option value="-1">&lt;-- Please select an option --&gt;</option>
  <option value="Association">Association</option>
  <option value="Close Corporation">Close Corporation</option>
  <option value="Foreign Company">Foreign Company</option>
  <option value="Non Resident Company">Non Resident Company</option>
  <option value="Partnership">Partnership</option>
  <option value="Private Company">Private Company</option>
  <option value="Public Company">Public Company</option>
  <option value="Section 21">Section 21</option>
  <option value="Sole Proprietor">Sole Proprietor</option>
  <option value="Trust">Trust</option>
                  </select>
                  </div></li>

                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Registration Number *"
                    name="reg_number"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="VAT NUMBER *"
                    name="vat_number"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Email Address #1"
                    name="email_address_1"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Email Address #2"
                    name="email_address_2"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Email Address #3"
                    name="email_address_3"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Telephone Number #1"
                    name="telephone_number_1"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Telephone Number #2"
                    name="telephone_number_2"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item is-filled">
                    <vmd-input
                    id=""
                    type="text"
                    label="Fax Number"
                    name="fax_number"
                  />
                  </li>
                   <li class="px-0 border-0 list-group-item is-filled">
                    <vmd-input
                    id=""
                    type="text"
                    label="Website"
                    name="website"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <p>Business Address</p>
                    <vmd-textarea
                    id=""
                    label="Business Address"
                    name="business_address"
                  />
                  </li>

                  <li class="px-0 border-0 list-group-item">
                   <vmd-input
                    id=""
                    type="text"
                    label="Postal Code"
                    name="business_address_postal_code"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item">
                    <p>Postal Address</p>
                    <vmd-textarea
                    id=""
                    label="Postal Address"
                    name="postal_address"
                  />
                  </li>
                  <li class="px-0 border-0 list-group-item null is-filled">
                   <vmd-input
                    id=""
                    type="text"
                    label="Postal Code"
                    name="postal_address_code"
                  />
                  </li>
                </ul>
                <button type="submit" class="btn btn-info ms-2 float-right">Create</button>
            </form>
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

                 

            <div class="d-flex justify-content-center align-items-center mb-4">
              <div class="form-outline flex-fill">
                <input type="text" id="form2" class="form-control" />
                <label class="form-label" for="form2">New task...</label>
              </div>
              <button type="submit" class="btn btn-info ms-2">Add</button>
            </div>


            <!-- Tabs content -->
            <div class="tab-content" id="ex1-content">
              <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel"
                aria-labelledby="ex1-tab-1">
                <ul class="list-group mb-0">
                 
                  <li class="list-group-item d-flex align-items-center border-0 mb-0 rounded"
                    style="background-color: #f4f6f7;">
                    <input class="form-check-input me-2" type="checkbox" value="" aria-label="..." />
                    Vestibulum at eros
                  </li>
                </ul>
              </div>
            </div>
            <!-- Tabs content -->

              </div>
            </div>
          </div>
        
        </div>
        
      </div>
    </div>
  </div>
  </Layout>
</template>
