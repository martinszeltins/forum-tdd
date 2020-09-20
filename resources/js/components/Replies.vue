<template>
    <div>
        <div v-for="(reply, index) in items">
            <reply
                :data="reply"
                :key="reply.id"
                @deleted="remove(index)">
            </reply>
        </div>

        <paginator 
            :dataSet="dataSet"
            @change="fetch"
        />

        <new-reply
            @created="add">
        </new-reply>
    </div>
</template>

<script>
    import Reply from './Reply.vue'
    import NewReply from './NewReply.vue'
    import Collection from '../mixins/Collection.js'

    export default {
        components:
        {
            Reply,
            NewReply,
        },

        mixins:
        [
            Collection
        ],

        data()
        {
            return {
                dataSet: false,
            }
        },

        methods:
        {
            url(page)
            {
                if (!page) {
                    let query = location.search.match(/page=(\d+)/)
                    
                    if (query) {
                        query = query[1]
                    }

                    page = query ? query : 1
                }

                return `${location.pathname}/replies?page=${page}`
            },

            async fetch(page)
            {
                const result = await axios.get(this.url(page))

                this.refresh(result.data)
            },

            refresh(data)
            {
                this.dataSet = data
                this.items = data.data

                window.scrollTo(0, 0);
            },
        },

        created()
        {
            this.fetch()
        },
    }
</script>