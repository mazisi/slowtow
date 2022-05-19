<template>
  <div
    class="w-auto h-auto collapse navbar-collapse max-height-vh-100 h-100"
    id="sidenav-collapse-main"
  >
    <ul class="navbar-nav">
      <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/dashboard"
          navText="Dashboard"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">dashboard</i>
          </template>
        </sidenav-collapse>
      </li>

       <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/licences"
          navText="Licences"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">receipt_long</i>
          </template>
        </sidenav-collapse>
      </li>
      <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/companies"
          navText="Companies"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">table_view</i>
          </template>
        </sidenav-collapse>
      </li>
     
    
      <li class="mt-3 nav-item"><h6 class="text-xs ps-4 text-uppercase font-weight-bolder text-white ms-2"> ACTIONS </h6></li>


      <li v-if="$page.props.currentRoute == 'companies'" class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/create-company"
          navText="New Company"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add</i>
          </template>
        </sidenav-collapse>
      </li>

      <li v-if="$page.props.currentRoute == 'licences'" class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/create-licence"
          navText="New Licence"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">add</i>
          </template>
        </sidenav-collapse>
      </li>
<hr>

 <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/people"
          navText="People"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5"
              >account_circle</i
            >
          </template>
        </sidenav-collapse>
      </li>
      <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/notifications"
          navText="Reports"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">notifications</i>
          </template>
        </sidenav-collapse>
      </li>

      <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="notifications"
          navText="Email Comms"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">email</i>
            
          </template>
        </sidenav-collapse>
      </li>
      
      <li class="nav-item">
        <sidenav-collapse
          url="#"
          :aria-controls="''"
          v-bind:collapse="false"
          collapseRef="/logout"
          navText="Logout"
        >
          <template v-slot:icon>
            <i class="material-icons-round opacity-10 fs-5">format_textdirection_r_to_l</i>
          </template>
        </sidenav-collapse>
      </li>
   
    </ul>
   
  </div>
</template>
<script>
import SidenavCollapse from "./SidenavCollapse.vue";

export default {
  name: "SidenavList",
  props: {
    cardBg: String,
  },
  data() {
    return {
      title: "Soft UI Dashboard PRO",
      controls: "dashboardsExamples",
      isActive: "active"
    };
  },
  components: {
    SidenavCollapse
  }
};
</script>
