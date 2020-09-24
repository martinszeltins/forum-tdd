<template>
    <div>
        <div v-if="signedIn">
            <div class="m-40-20">
                <div class="form-group">
                    <at-ta :members="members" @at="fetchMembers">
                        <textarea
                            name="body"
                            id="body"
                            class="form-control"
                            placeholder="Have something to say?"
                            rows="5"
                            v-model="body"
                            required>
                        </textarea>
                    </at-ta>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    @click="addReply">
                    Post
                </button>
            </div>
        </div>

        <p v-if="!signedIn" class="text-center">Please <a href="/login">
            sign in</a> to participate in this discussion.
        </p>
    </div>
</template>

<script>
    import debounce from 'debounce'
    import AtTa from 'vue-at/dist/vue-at-textarea'

    export default {
        components: 
        {
            AtTa,
        },

        data()
        {
            return {
                body: '',
                endpoint: location.pathname + '/replies',
                members: [],
            }
        },

        methods:
        {
            async fetchMembers(at)
            {
                const result = await axios.get(`/api/users?name=${at}`)
                this.members = result.data
            },

            async addReply()
            {
                console.log(this)
                try {
                    var result = await axios.post(this.endpoint, {
                        body: this.body
                    })
                } catch (error) {
                    flash(error.response.data, 'danger')
                    return
                }

                this.body = ''
                flash('Your reply has been posted.')
                this.$emit('created', result.data)
            },
        },

        computed:
        {
            signedIn()
            {
                return window.App.signedIn
            },
        },

        created()
        {
            this.fetchMembers = debounce(this.fetchMembers, 500)
        },
    }
</script>