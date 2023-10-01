<template>

    <ResourceIndex
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="null"
        :relationship-type="relationshipType"
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
        data() {
            return {
                eventCallback: null,
            }
        },
        mounted() {
            Nova.$on('custom-relationship-field:request-extra-params', this.eventCallback = () => {
                Nova.$emit('custom-relationship-field:extra-params', { relationshipType: this.relationshipType })
            })
        },
        unmounted() {
            Nova.$off('custom-relationship-field:request-extra-params', this.eventCallback)
        },
        computed: {
            encodedAttribute() {
                return btoa(`${ this.field.attribute }|_::_|${ this.field.name }`)
            },
            relationshipType() {
                return `CustomRelationshipField:${ this.encodedAttribute }`
            },
        },
    }

</script>
