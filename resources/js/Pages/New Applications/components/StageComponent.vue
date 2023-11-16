<template>
  <div :class="'col-md-' + column" class="columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox"     
    :id="stageTitle" type="checkbox" 
    @input="emitValue($event,stageValue)" 
    :checked="dbStatus >= stageValue" 
    :value="stageValue"
    />
    <label :for="stageTitle" 
    class="form-check-label text-body text-truncate status-heading">{{ stageTitle }} + {{stageValue}}</label>
    </div>
  </div> 
</template>

<script>

export default{
  
  props: {
    dbStatus: Object,
    column: String,
    errors: Object,
    model_id: Number,
    stageValue: Number,
    stageTitle: String,
    success: Object
  },
  setup(props, context){
    function emitValue(event) {
      context.emit('stage-value-changed', event, props.stageValue);
    }

    return {
      emitValue
    }
  }
}
</script>
<style scoped>
.columns{
  margin-bottom: 1rem;
}

.active-checkbox{
  margin-top: -10px;
  margin-left: 3px;
}
.status-heading{
  font-weight: 700;
}
</style>