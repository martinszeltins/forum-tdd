<script>
    import Replies from '../components/Replies.vue'
    import SubscribeButton from '../components/SubscribeButton.vue'

    export default {
        props: ['thread'],

        components:
        {
            Replies,
            SubscribeButton,
        },

        data()
        {
            return {
                repliesCount: this.thread.replies_count,
                locked: this.thread.locked,
            }
        },

        methods:
        {
            toggleLock()
            {
                let method = this.locked ? 'delete' : 'post'
                
                this.locked = !this.locked

                axios[method](`/locked-threads/${this.thread.slug}`)
            },
        },
    }
</script>