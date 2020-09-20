<template>
    <button
        @click="subscribe"
        class="btn"
        :class="active ? 'btn-primary' : 'btn-secondary'">
        Subscribe
    </button>
</template>

<script>
    export default
    {
        props: ['active'],

        methods:
        {
            subscribe()
            {
                let requestType = this.active ? 'delete' : 'post'

                axios[requestType](location.pathname + '/subscriptions')

                this.active = !this.active
            },
        },
    }
</script>