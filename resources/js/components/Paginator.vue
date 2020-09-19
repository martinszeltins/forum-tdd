<template>
    <nav
        v-if="shouldPaginate"
        aria-label="Page navigation example">

        <ul class="pagination">
            <li v-show="prevUrl" class="page-item">
                <a class="page-link" href="#" @click.prevent="page--">
                    Previous
                </a>
            </li>

            <li v-show="nextUrl" class="page-item">
                <a class="page-link" href="#" @click.prevent="page++">
                    Next
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    export default {
        props: ['dataSet'],

        data()
        {
            return {
                page: 1,
                prevUrl: false,
                nextUrl: false,
            }
        },

        methods:
        {
            broadcast()
            {
                return this.$emit('change', this.page)
            },

            updateUrl()
            {
                history.pushState(null, null, `?page=${this.page}`)
            },
        },

        computed:
        {
            shouldPaginate()
            {
                return !! this.prevUrl || !! this.nextUrl
            },
        },

        watch:
        {
            dataSet()
            {
                this.page = this.dataSet.current_page
                this.prevUrl = this.dataSet.prev_page_url
                this.nextUrl = this.dataSet.next_page_url
            },

            page()
            {
                this.broadcast().updateUrl()
            },
        },
    }
</script>