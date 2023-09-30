<template>

    <ResourceIndex
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="null"
        :relationship-type="`CustomRelationshipField:${ encodedAttribute }`"
        @actionExecuted="actionExecuted"
        :load-cards="false"
        :initial-per-page="field.perPage || 5"
        :should-override-meta="false"
        :custom-relationship-field-attribute="field.attribute"
        :custom-relationship-field-label="field.name"
    />

</template>

<script>

    export default {
        emits: [ 'actionExecuted' ],
        props: [ 'resourceName', 'resourceId', 'resource', 'field' ],
        methods: {
            actionExecuted() {
                this.$emit('actionExecuted')
            },
        },
        computed: {
            encodedAttribute() {
                return btoa(`${ this.field.attribute }|_::_|${ this.field.name }`)
            },
        }
    }

</script>
