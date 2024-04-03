<template>
  <div :class="'col-md-' + column" class="columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox"
    :id="stageValue" type="checkbox"
    @input="emitValue($event,stageValue,prevStage)"
    :checked="dbStatus >= stageValue"
    :value="stageValue"
    :disabled="(stageValue === 2300 || stageValue === 2400) && canActivate === null"
    />
    <label :for="stageValue"
    class="form-check-label text-body text-truncate status-heading">{{ stageTitle }}</label>
    </div>
  </div>
</template>

<script>

export default{
  name: 'StageComponent',
  props: {
    dbStatus: Object,
    column: Number,
    errors: Object,
    error: String,
    model_id: Number,
    stageValue: Number,
    prevStage: Number,
    stageTitle: String,
    success: String,
    canActivate: Date,
  },
  setup(props, context){
    function emitValue(event) {
      context.emit('stage-value-changed', event, props.stageValue, props.prevStage,props.stageTitle);
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
