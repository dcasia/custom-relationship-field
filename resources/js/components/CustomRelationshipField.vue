<template>

    <IndexComponent :field="field"
                    :resource-name="field.resourceName"
                    :via-resource="resourceName"
                    :via-resource-id="resourceId"
                    :via-relationship="null"
                    :relationship-type="'has-many'"
                    @actionExecuted="actionExecuted"
                    :load-cards="false"
                    :initialPerPage="field.perPage || 5"
                    :custom-relationship-field-attribute="field.attribute"
                    :custom-relationship-field-label="field.name"/>

</template>

<script>

    import IndexComponent from './CustomIndexComponent'

    export default {
        props: [ 'resourceName', 'resourceId', 'resource', 'field' ],
        components: { IndexComponent },
        methods: {
            /**
             * Handle the actionExecuted event and pass it up the chain.
             */
            actionExecuted() {
                this.$emit('actionExecuted')
            }
        }
    }

</script>
