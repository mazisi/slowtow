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
      <div
      class="invisible pe-md-3 d-flex align-items-center"
      :class="isRTL ? 'me-md-auto' : 'ms-md-auto'">
      
    </div>
        <ul class="navbar-nav justify-content-end">        
         
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
<style scoped>
.columns{
  margin-bottom: 1rem;
}
#active-checkbox{
  margin-left: 3px;
}

</style>
<script>
import { mapMutations, mapState } from "vuex";
import { Inertia } from '@inertiajs/inertia'

export default {
  name: "navbar",
  props: {
    minNav: String,
    color: String,
    error: String,
    success: String
  },

  data() {

    return {
      showMenu: false,
    };
  },
  
  created() {
    this.minNav;
    
  },
  methods: {    
    ...mapMutations(["navbarMinimize", "toggleConfigurator", "onInertiaVisit"]),
    
    toggleSidebar() {
      this.navbarMinimize();
    },
  },
  components: {
    Inertia
  },
  computed: {
    ...mapState(["isRTL", "isAbsolute"]),

    
  },
};
</script>
