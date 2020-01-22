<script>

    import IndexComponent from '~~nova~~/views/Index.vue'

    export default {
        extends: IndexComponent,
        props: [ 'customRelationshipFieldAttribute', 'customRelationshipFieldLabel' ],
        beforeCreate() {

            const interceptor = Nova.request().interceptors.request.use(
                config => {

                    if (config.url.match(/nova-api\/\S.+\/(filters|actions?)/)) {

                        config.params[ 'customRelationshipFieldAttribute' ] = this.customRelationshipFieldAttribute
                        config.params[ 'customRelationshipFieldLabel' ] = this.customRelationshipFieldLabel

                    }

                    return config

                }
            )

            this.$on('hook:destroyed', () => Nova.request().interceptors.request.eject(interceptor))

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
        }
    }

</script>
