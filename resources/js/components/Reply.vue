<script>
    import Favorite from './Favorite.vue'

    export default {
        props: ['attributes'],

        components:
        {
            Favorite,
        },

        data()
        {
            return {
                editing: false,
                body: this.attributes.body,
                updateBtn: 'Update',
                deleteBtn: 'Delete',
            }
        },

        methods:
        {
            async openEditor()
            {
                this.editing = !this.editing

                await this.$nextTick()
                this.$refs.edit_input.focus()
            },
        
            async update()
            {
                this.updateBtn = 'Saving...'

                await axios.patch(`/replies/${this.attributes.id}`, {
                    body: this.body,
                })

                this.updateBtn = 'Update'

                this.editing = false

                flash('Updated!')
            },
        
            async destroy()
            {
                this.deleteBtn = 'Deleting...'

                await axios.delete(`/replies/${this.attributes.id}`)

                this.deleteBtn = 'Delete'

                $(this.$el).fadeOut(300, () => {
                    flash('Deleted!')
                })
            },
        },
    }
</script>