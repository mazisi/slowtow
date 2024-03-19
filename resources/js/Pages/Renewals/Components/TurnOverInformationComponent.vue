<script >

export default {
    name: 'TurnOverInformationComponent',
}
</script>

<template>
    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Beer</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Wine</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Volume of Spirit/Other</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Exact Turnover Amount</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Number of Employees</label>
            <input type="text" class="form-control form-control-default">
        </div>
    </div>

    <div class="col-md-6 columns mb-4">
        <div class="input-group input-group-outline null is-filled">
            <label class="form-label">Annual Turnover</label>
            <select class="form-select">
                <option value="Less than R5 Million">Less than R5 Million</option>
                <option value="Between R5 Million and R15 Million">Between R5 Million and R15 Million</option>
                <option value="Between R15 Million and R250 Million">Between R15 Million and R250 Million</option>
                <option value="Between R250 Million and R1 Billion">Between R250 Million and R1 Billion</option>
                <option value="More than R1 Billion">More than R1 Billion</option>
                <option value="Dormant">Dormant</option>

            </select>
        </div>
    </div>

    <div class="col-md-6 columns">
        <button type="button" class="btn btn-sm btn-secondary">Save</button>
    </div>
</template>

<style scoped>

</style>
