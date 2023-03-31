<template>
    <div :class="column" class=" columns">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">{{ label }}</label>
            <select @change="$emit('update:modelValue', $event.target.value)" class="form-control form-control-default">
                 <option v-for='licence_dropdown in dropdownList' :value=licence_dropdown.id> {{ licence_dropdown.licence_type }}</option>
            </select>
        </div>
<div v-if="errors" class="text-danger">{{ errors }}</div>
</div>

</template>

<script>
export default{
    props: {
        errors: Object,
        dropdownList: Object,
        label: String,
        col: Number,
        defaultDisabledText: String
    }
}
</script>