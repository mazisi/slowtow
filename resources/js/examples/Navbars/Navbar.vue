<template>
  <nav
    class="shadow-none navbar navbar-main navbar-expand-lg border-radius-xl"
    v-bind="$attrs"
    id="navbarBlur"
    data-scroll="true"
    :class="isAbsolute ? 'mt-4' : 'mt-0'"
  >
    <div class="px-3 py-1 container-fluid">
      <breadcrumbs :currentPage="company" :color="color" />
      <div
        class="mt-2 collapse navbar-collapse mt-sm-0 me-md-0 me-sm-4"
        :class="isRTL ? 'px-0' : 'me-sm-4'"
        id="navbar"
      >


   <div class="mx-auto" v-if="($page.props.success && showFlashMessage)" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">

      <div class="alert text-white alert-success alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ $page.props.success }}</span></span><button @click="showFlashMessage = false" type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
 </div>

      <div v-else-if="($page.props.error && showFlashMessage)" class="mx-auto" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
      <div class="alert text-white alert-danger alert-dismissible fade show font-weight-light" role="alert">
      <span class="alert-icon"><i class=""></i></span><span class="alert-text"> 
      <span class="text-sm">{{ $page.props.error }}</span></span><button @click="showFlashMessage = false" type="button" class="btn-close d-flex justify-content-center align-items-center" data-bs-dismiss="alert" aria-label="Close">
      <span aria-hidden="true" class="text-lg font-weight-bold">×</span>
      </button>
      </div>
      </div>


        <div
          class="invisible pe-md-3 d-flex align-items-center"
          :class="isRTL ? 'me-md-auto' : 'ms-md-auto'"
        >
          <vmd-input id="search" label="Search here" />
        </div>
        <ul class="navbar-nav justify-content-end">
          <li class="nav-item d-flex align-items-center">
            <inertia-link
              :href="`/logout`"
              class="px-0 nav-link font-weight-bold lh-1 "
              :class="color ? color : 'text-body'"
            >
              <i class="material-icons text-danger" :class="isRTL ? 'ms-sm-2' : 'me-sm-1'">
                logout
              </i>
            </inertia-link>
          </li>
          
         
         
          <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
            <a
              href="#"
              @click="toggleSidebar"
              class="p-0 nav-link text-body lh-1"
              id="iconNavbarSidenav"
            >
              <div class="sidenav-toggler-inner">
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
                <i class="sidenav-toggler-line"></i>
              </div>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>
<script>
import VmdInput from "@/Components/VmdInput.vue";
import Breadcrumbs from "../Breadcrumbs.vue";
import { mapMutations, mapState } from "vuex";

export default {
  name: "navbar",
  data() {
    return {
      showMenu: false,
      showFlashMessage: false,
    };
  },
  watch: {
    '$page.props.success': {
      handler(newVal,preVal) {
        if(this.$page.props.success != null){
          this.showFlashMessage = true
        }
        
      },
      deep: true,
    },

    '$page.props.error': {
      handler(newVal,preVal) {
        if(this.$page.props.error != null){
          this.showFlashMessage = true
        }
        
        if(this.$page.props.error != null){
          this.showFlashMessage = true
        } 
      },
      deep: true,
    },
    },
  props: ["minNav", "color"],
  created() {
    this.minNav;
  },
  methods: {
    ...mapMutations(["navbarMinimize", "toggleConfigurator"]),

    toggleSidebar() {
      this.navbarMinimize();
    },
  },
  components: {
    Breadcrumbs,
    VmdInput,
  },
  computed: {
    ...mapState(["isRTL", "isAbsolute"]),

    
  },
};
</script>
