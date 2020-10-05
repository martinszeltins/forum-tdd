<template>
    <div
        :id="'reply-' + id"
        class="card m-3"
        :class="isBest ? 'panel-success' : 'panel-default'">

        <div class="card-header">
            <div class="level">
                <h5 class="flex">
                    <a :href="'/profiles/' + data.owner.name">
                        {{ data.owner.name}}
                    </a>

                    said {{ ago }}
                </h5>

                <div v-if="signedIn">
                    <favorite :reply="data"></favorite>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="form-group">
                    <textarea
                        v-model="body"
                        class="form-control"
                        ref="edit_input">
                    </textarea>
                </div>

                <button
                    @click="update"
                    class="btn btn-xs btn-primary">

                    <div v-text="updateBtn"></div>
                </button>
                
                <button
                    @click="editing = false"
                    class="btn btn-xs btn-link">
                    Cancel
                </button>
            </div>

            <div v-else v-html="body"></div>
        </div>

        <div class="panel-footer level margin-20">
            <div v-if="authorize('updateReply', reply)">
                <button
                    @click="openEditor"
                    class="btn btn-secondary btn-sm mr-1">
                    Edit
                </button>

                <button
                    @click="destroy"
                    class="btn btn-danger btn-sm mr-1"
                    v-text="deleteBtn">
                </button>
            </div>
            
            <button
                v-if="!isBest"
                @click="markBestReply"
                class="btn btn-secondary btn-sm ml-a">
                Best reply?
            </button>
        </div>
    </div>
</template>

<script>
    import Favorite from './Favorite.vue'
    import moment from 'moment'

    export default {
        props: ['data'],

        components:
        {
            Favorite,
        },

        data()
        {
            return {
                editing: false,
                id: this.data.id,
                body: this.data.body,
                updateBtn: 'Update',
                deleteBtn: 'Delete',
                isBest: this.data.isBest,
                reply: this.data,
            }
        },

        methods:
        {
            markBestReply()
            {
                axios.post(`/replies/${this.data.id}/best`)

                window.events.$emit('best-reply-selected', this.data.id)
            },

            async openEditor()
            {
                this.editing = !this.editing

                await this.$nextTick()
                this.$refs.edit_input.focus()
            },
        
            async update()
            {
                this.updateBtn = 'Saving...'

                try {
                    await axios.patch(`/replies/${this.data.id}`, {
                        body: this.body,
                    })
                } catch (error) {
                    flash(error.response.data, 'danger')
                    this.updateBtn = 'Update'
                    return
                }

                this.updateBtn = 'Update'

                this.editing = false

                flash('Updated!')
            },
        
            async destroy()
            {
                this.deleteBtn = 'Deleting...'

                await axios.delete(`/replies/${this.data.id}`)

                this.$emit('deleted', this.data.id)

                this.deleteBtn = 'Delete'
            },
        },

        computed:
        {
            ago()
            {
                return moment(this.data.created_at).fromNow()
            },
        },

        created()
        {
            window.events.$on('best-reply-selected', id => {
                this.isBest = (id === this.id)
            })
        },
    }
</script>