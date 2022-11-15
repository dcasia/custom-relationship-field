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

    import { mapProps } from '@/mixins'

    export default {
        components: { IndexComponent },
        emits: [ 'actionExecuted' ],

        props: {
            ...mapProps([ 'resourceId', 'field' ]),
            resourceName: {},
            resource: {},
        },

        methods: {
            /**
             * Handle the actionExecuted event and pass it up the chain.
             */
            actionExecuted() {
                this.$emit('actionExecuted')
            },
        },
    }

</script>
