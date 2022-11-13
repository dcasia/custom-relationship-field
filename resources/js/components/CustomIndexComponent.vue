<script>

    import IndexComponent from '@/pages/Index.vue'

    export default {
        extends: IndexComponent,
      props: {
        customRelationshipFieldAttribute: {
          type: String,
          required: true
        },
        customRelationshipFieldLabel: {
          type: String,
          required: true
        }
      },
        computed: {
            /**
             * Build the resource request query string.
             */
            resourceRequestQueryString() {
                return {
                    search: this.currentSearch,
                    filters: this.encodedFilters,
                    orderBy: this.currentOrderBy,
                    orderByDirection: this.currentOrderByDirection,
                    perPage: this.currentPerPage,
                    trashed: this.currentTrashed,
                    page: this.currentPage,
                    viaResource: this.viaResource,
                    viaResourceId: this.viaResourceId,
                    viaRelationship: this.viaRelationship,
                    viaResourceRelationship: this.viaResourceRelationship,
                    relationshipType: this.relationshipType,
                    customRelationshipFieldAttribute: this.customRelationshipFieldAttribute,
                    customRelationshipFieldLabel: this.customRelationshipFieldLabel
                }
            }
        },
        beforeCreate() {

            const interceptor = Nova.request().interceptors.request.use(
                config => {

                    if (/^\/nova-api\/\S.+\/(filters|action)$/.test(config.url)) {

                        if (config.method === 'post' && config.params.viaResourceId === '') {

                            return config

                        }

                        config.params[ 'customRelationshipFieldAttribute' ] = this.customRelationshipFieldAttribute
                        config.params[ 'customRelationshipFieldLabel' ] = this.customRelationshipFieldLabel

                    }

                    return config

                }
            )

            Nova.$on('hook:destroyed', () => Nova.request().interceptors.request.eject(interceptor))

        },
        methods: {

            /**
             * Get the actions available for the current resource.
             */
            getActions() {

                this.actions = []
                this.pivotActions = null

                return Nova.request()
                    .get(`/nova-api/${ this.resourceName }/actions`, {
                        params: {
                            viaResource: this.viaResource,
                            viaResourceId: this.viaResourceId,
                            viaRelationship: this.viaRelationship,
                            relationshipType: this.relationshipType,
                            customRelationshipFieldAttribute: this.customRelationshipFieldAttribute,
                            customRelationshipFieldLabel: this.customRelationshipFieldLabel
                        }
                    })
                    .then(response => {
                        this.actions = _.filter(response.data.actions, a => a.showOnIndex)
                        this.pivotActions = response.data.pivotActions
                    })

            }

        }
    }

</script>
