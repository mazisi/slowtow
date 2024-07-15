<template>
  <div :class="'col-md-' + column" class="columns">
    <div class=" form-switch d-flex ps-0 ms-0  is-filled">
    <input class="active-checkbox"
    :id="stageValue" type="checkbox"
    @input="emitValue($event,stageValue,prevStage)"
    :checked="dbStatus >= stageValue"
    :value="stageValue"
    />
    <label :for="stageValue"
    class="form-check-label text-body status-heading">{{ stageTitle }}</label>
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
    function truncateText(text, length) {
      if (text.length > length) {
        return text.slice(0, length) + '...';
      }
      return text;
    }
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
