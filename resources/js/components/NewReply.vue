<template>
    <div>
        <div v-if="signedIn">
            <div class="col-md-8">
                <div class="form-group">
                    <textarea
                        name="body"
                        id="body"
                        class="form-control"
                        placeholder="Have something to say?"
                        rows="5"
                        v-model="body"
                        required>
                    </textarea>
                </div>

                <button
                    type="submit"
                    class="btn btn-primary"
                    @click="addReply">
                    Post
                </button>
            </div>
        </div>

        <p class="text-center">Please <a href="/login">
            sign in</a> to participate in this discussion.
        </p>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                body: '',
                endpoint: location.pathname + '/replies',
            }
        },

        methods:
        {
            async addReply()
            {
                try {
                    const result = await axios.post(this.endpoint, {
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
    }
</script>