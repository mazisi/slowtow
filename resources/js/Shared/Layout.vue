<template>

  <sidenav
    :custom_class="color"
    class="fixed-start"
    v-if="showSidenav"
  />
  <main class="main-content position-relative max-height-vh-100 h-100 overflow-x-hidden">
    <!-- nav -->
    <navbar
      :class="[isNavFixed ? navbarFixed : '', isAbsolute ? absolute : '']"
      :color="isAbsolute ? 'text-white opacity-8' : ''"
      :minNav="navbarMinimize"
      v-if="showNavbar"
    />
   
    <slot/>
    <app-footer v-show="showFooter" />
 
  </main>
 
</template>
<style>
  .navbar-nav {
    overflow: hidden;
  }
</style>
<script>
import Sidenav from "../examples/Sidenav";
import Navbar from "@/examples/Navbars/Navbar.vue";
import AppFooter from "@/examples/Footer.vue";
import { mapMutations, mapState } from "vuex";
import { VOffline } from 'v-offline';
import { toast } from 'vue3-toastify'
import { slotFlagsText } from "@vue/shared";

export default {
  name: "App",

  data() {
      return { 
        online: false
      }
    },
  components: {
    Sidenav,
    Navbar,
    AppFooter,
    VOffline,
    toast
  },
  methods: {
    ...mapMutations(["toggleConfigurator", "navbarMinimize"]),

    onNetworkChange(status) {
        online.value = status;
        console.log('Msesh')
      }

    
  },
  computed: {
    ...mapState([
      "isRTL",
      "color",
      "isAbsolute",
      "isNavFixed",
      "navbarFixed",
      "absolute",
      "showSidenav",
      "showNavbar",
      "showFooter",
      "hideConfigButton"
    ])
  },

  
};
</script>
