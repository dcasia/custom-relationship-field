<template>
    <IndexComponent
        :field="field"
        :resource-name="field.resourceName"
        :via-resource="resourceName"
        :via-resource-id="resourceId"
        :via-relationship="null"
        :relationship-type="'has-many'"
        :load-cards="false"
        :initial-per-page="field.perPage || 5"
        :custom-relationship-field-attribute="field.attribute"
        :custom-relationship-field-label="field.name"
        @action-executed="actionExecuted"
    />
</template>

<script>

import IndexComponent from './CustomIndexComponent'

export default {
  components: { IndexComponent },
  props: {
    field: {
      type: Object,
      required: true
    },
    resource: {
      type: Object,
      required: true
    },
    resourceName: {
      type: String,
      required: true
    },
    resourceId: {
      type: String,
      required: true
    }
  },
  emits: {'actionExecuted': null},
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
