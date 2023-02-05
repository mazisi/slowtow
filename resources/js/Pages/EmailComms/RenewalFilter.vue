<template>
  <div class="input-group input-group-outline null is-filled" >
    <Multiselect 
      v-model="form.province"          
      :options="renewal_stages"
       mode="tags"
      :taggable="true"
      placeholder="Select Renewal Stages"/>
  </div>
</template>

<script>
// import Multiselect from '@vueform/multiselect';

export default {

  setup() {
    const renewal_stages = {
    "1": "Client Quoted",
    "2" : "Client Invoiced",
    "3" : "Client Paid",
    "4":  "Payment To Liquor Board",
    "5": "Renewal Issued",
    "6": "Renewal Delivered"
    }

    return {
      renewal_stages
    }
  },

  components: {
    Multiselect
  },
};

</script>