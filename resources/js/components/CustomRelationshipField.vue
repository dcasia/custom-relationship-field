<template>

    <IndexComponent
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="null"
        :relationship-type="'hasMany'"
        @actionExecuted="actionExecuted"
        :load-cards="false"
        :initialPerPage="field.perPage || 5"
        :should-override-meta="false"
        :custom-relationship-field-attribute="field.attribute"
        :custom-relationship-field-label="field.name"
    />

</template>

<script>

    import IndexComponent from './CustomIndexComponent'

    export default {
        components: { IndexComponent },
        emits: [ 'actionExecuted' ],
        props: [ 'resourceName', 'resourceId', 'resource', 'field' ],
        methods: {
            actionExecuted() {
                this.$emit('actionExecuted')
            },
        },
    }

</script>
