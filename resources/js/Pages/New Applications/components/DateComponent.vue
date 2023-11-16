<template>
  <div :class="'col-md-' + column" class="columns">
    <div class="input-group input-group-outline null is-filled">
    <label class="form-label">Date</label>
    <input type="date" class="form-control form-control-default" v-model="dated_at">
     </div>
   <div v-if="errors.dated_at" class="text-danger">{{ errors.dated_at }}</div>
  </div>
  <div class="col-md-1 columns">
    <button v-if="canSave"     
    @click="emitValue" type="button" class="btn btn-sm btn-secondary">Save</button>
  </div>
</template>

<script>

export default{
  
  props: {
    canSave: Boolean,
    errors: Object,
    column: Number,
    success: Object,
    dated_at: Date
  },
  setup(props, context){
    const dated_at =props.dated_at;
    function emitValue(event) {
      //context.emit('pick-document', dated_at);
    }

    return {
      emitValue,dated_at
    }
  }
}
</script>