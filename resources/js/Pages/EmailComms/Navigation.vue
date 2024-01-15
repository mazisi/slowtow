<template>
    <ul class="nav mb-3 pt-3" id="pills-tab" role="tablist">

        <li class="nav-item" role="presentation">
          <button @click="getLicenceRenewals" class="nav-link btn text-white" 
          :class="$page.props.currentRoute == 'email_comms' ? 'btn-success' : 'btn-secondary'"  
          id="Renewals" 
          data-bs-toggle="pill" data-bs-target="#renewals" 
          type="button" role="tab" aria-controls="renewals" aria-selected="true">Renewals</button>
        </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        <li class="nav-item" role="presentation">
          <button @click="getLicenceTransfers" 
          :class="$page.props.currentRoute == 'get_licence_transfers' ? 'btn-success' : 'btn-secondary'" 
          class="nav-link btn text-white ml-4" 
          id="Transfers" data-bs-toggle="pill" data-bs-target="#transfers" 
          type="button" role="tab" aria-controls="transfers" aria-selected="false">Transfers</button>
        </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        <li class="nav-item" role="presentation">
          <button @click="getNominations" 
          :class="$page.props.currentRoute == 'get_nominations' ? 'btn-success' : 'btn-secondary'" 
          class="nav-link btn text-white" id="Nominations" 
          data-bs-toggle="pill" data-bs-target="#nominations" 
          type="button" role="tab" aria-controls="nominations" aria-selected="false">Nominations</button>
        </li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      
        <li class="nav-item" role="presentation">
          <button @click="getAlterations" :class="$page.props.currentRoute == 'get_alterations' ? 'btn-success' : 'btn-secondary'" 
          class="nav-link btn text-white" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
          type="button" role="tab" aria-controls="alterations" aria-selected="false">Alterations</button>
        </li>
      
        <li class="nav-item" role="presentation">
          <button @click="getNewApps" 
          :class="$page.props.currentRoute == 'get_new_app_template' ? 'btn-success' : 'btn-secondary'" 
          class="nav-link btn text-white mx-4" id="New Applications" data-bs-toggle="pill" data-bs-target="#new-apps" 
          type="button" role="tab" aria-controls="new-apps" aria-selected="false">New Applications</button>
        </li>
      
        <li class="nav-item" role="presentation">
          <button @click="getTemporaryLicences" 
          :class="$page.props.currentRoute == 'get_temp_licences' ? 'btn-success' : 'btn-secondary'" 
          class="nav-link btn text-white mx-2" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
          type="button" role="tab" aria-controls="alterations" aria-selected="false">Temporary Licences</button>
        </li>
      
        <!-- <li class="nav-item" role="presentation">
          <button @click="getEmmails" :class="$page.props.currentRoute ? 'btn-success' : 'btn-secondary'" class="nav-link text-white mx-4" id="Alterations" data-bs-toggle="pill" data-bs-target="#alterations" 
          type="button" role="tab" aria-controls="alterations" aria-selected="false"> Emails</button>
        </li> -->
        
      </ul>
</template>
<script>
import { Inertia } from '@inertiajs/inertia';


export default {
    setup(props) {
    //On navigation click get renewal data
    function getLicenceRenewals(){
      Inertia.get('/email-comms');
    }

    //On navigation click get transfer data
    function getLicenceTransfers(){
      Inertia.get('/email-comms/transfers');
    }
    function getNominations(){
      Inertia.get('/email-comms/nominations');
    }
    function getTemporaryLicences(){
      Inertia.get('/email-comms/temp-licences');
    }

    function getAlterations(){
      Inertia.get('/email-comms/alterations');
    }

    function getNewApps(){
      Inertia.get('/email-comms/new-apps');
    }

    function getEmmails(){
      Inertia.get('/emails-report');
    }
    return {
        getNewApps,
        getAlterations,
        getNominations,
        getLicenceTransfers,
        getLicenceRenewals,
        getTemporaryLicences
}
}
}
</script>