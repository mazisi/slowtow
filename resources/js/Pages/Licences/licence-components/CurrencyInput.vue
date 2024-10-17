<template>
  <div class="col-12 columns">
    <div class="input-group input-group-outline null is-filled ">
      <label :for="input_id" class="form-label">{{ label }}</label>
      <input type="text"
        v-model="formattedValue"
        @input="onInput"
        @blur="formatCurrency"
        @focus="removeFormatting"
        class="form-control form-control-default"
      />
    </div>
    <div v-if="errors" class="text-danger">{{ errors }}</div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue';

const props = defineProps({
  input_id: String,
  errors: Object,
  label: String,
  modelValue: String,  // This is the value passed from the parent using v-model
});

const emit = defineEmits(['update:modelValue']);  // Emit the update to the parent

const inputValue = ref(props.modelValue);  // Local copy of the value
const pattern = /^[0-9\b]+$/;

// Watch for changes in the prop and update inputValue when necessary
watch(() => props.modelValue, (newValue) => {
  inputValue.value = newValue;
});

// Computed property to handle formatted value
const formattedValue = computed({
  get() {
    return formatCurrency(inputValue.value);
  },
  set(value) {
    // Strip out non-numeric characters before emitting the change to the parent
    const cleanedValue = value.replace(/\D/g, '');
    console.log('cleanedValue', cleanedValue);
    inputValue.value = cleanedValue;
    emit('update:modelValue', cleanedValue);  // Emit the cleaned value back to the parent
  },
});

// Function to format the input as currency (ZAR)
const formatCurrency = (valueParam) => {
  if (!valueParam) return '';
  if(!pattern.test(valueParam)) return "";
  const numberValue = Number(valueParam.replace(/\D/g, ''));
  return new Intl.NumberFormat('en-ZA', {
    style: 'currency',
    currency: 'ZAR',
  }).format(numberValue / 100);
};

// Function to remove formatting when input is focused for editing
const removeFormatting = () => {
  if(!inputValue.value) return;
  inputValue.value = inputValue?.value.replace(/[^\d]/g, '');
};

// Handle input event to clean up non-numeric characters
const onInput = (event) => {
  const target = event.target;
  
  if (!pattern.test(target.value)) {
    return false;
  }
  inputValue.value = target.value?.replace(/\D/g, '');
};
</script>
