<template>
    <div class="row">
        <div class="col-md-6">{{ label }}</div>
        <div class="col-md-6">
          <input 
            :type="type"
            :value="value"
            class="form-control"
            :required="true"
            disabled="true"
          />
        </div>
      </div>
</template>
<script>
export default {
    props: {
        value: String,
        label: String,
        type: String,
    },
}
</script>