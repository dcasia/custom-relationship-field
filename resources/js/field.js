import CustomRelationshipField from './components/CustomRelationshipField'

Nova.booting((app, store) => {
    app.component('detail-custom-relationship-field', CustomRelationshipField)
})
