<script>

    import IndexComponent from '@/pages/Index.vue'
    import { defineComponent } from 'vue'

    const novaRequest = Nova.request

    const interceptors = []
    const interceptorsInstance = []

    Nova.request = (...params) => {

        for (const param of params) {

            for (const interceptor of interceptors) {
                interceptor(param)
            }

        }

        const axiosInstance = novaRequest(...params)

        if (axiosInstance instanceof Promise) {
            return axiosInstance
        }

        for (const interceptor of interceptors) {

            interceptorsInstance.push({
                instance: axiosInstance,
                interceptor: axiosInstance.interceptors.request.use(config => interceptor(config)),
            })

        }

        return axiosInstance

    }

    export default defineComponent({
        extends: IndexComponent,
        props: {
            customRelationshipFieldAttribute: {
                type: String,
                required: true,
            },
            customRelationshipFieldLabel: {
                type: String,
                required: true,
            },
        },
        beforeCreate() {

            interceptors.push(config => {

                if (config.params === undefined) {
                    config.params = {}
                }

                if (config.params.viaRelationship?.startsWith(`CustomRelationshipField:${ this.customRelationshipFieldAttribute }`)) {

                    delete config.params.viaRelationship

                    const regex = new RegExp(`^\/nova-api\/(${ this.resourceName })\/?(filters|actions|relate-authorization)?`)

                    if (regex.test(config.url)) {

                        config.params[ 'customRelationshipFieldAttribute' ] = this.customRelationshipFieldAttribute
                        config.params[ 'customRelationshipFieldLabel' ] = this.customRelationshipFieldLabel

                    }

                }

                return config

            })

        },
        beforeUnmount() {

            for (const { instance, interceptor } of interceptorsInstance) {
                instance.interceptors.request.eject(interceptor)
            }

            while (interceptorsInstance.length) {
                interceptorsInstance.pop()
                interceptors.pop()
            }

        },
    })

</script>
