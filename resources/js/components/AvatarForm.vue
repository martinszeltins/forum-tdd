<template>
    <div>
        <div class="level">
            <img
                :src="avatar"
                width="80"
                height="80"
                class="mr-2"
            />

            <h1>
                {{ user.name }}
            </h1>
        </div>

        <form
            v-if="canUpdate"
            method="POST"
            enctype="multipart/form-data">

            <image-upload
                name="avatar"
                @loaded="onLoad">
            </image-upload>
        </form>
    </div>
</template>

<script>
    import ImageUpload from './ImageUpload.vue'

    export default {
        props: ['user'],

        components:
        {
            ImageUpload,
        },

        data()
        {
            return {
                avatar: this.user.avatar_path,
            }
        },

        methods:
        {
            onLoad(avatar)
            {
                this.avatar = avatar.src

                this.persist(avatar.file)
            },

            async persist(avatar)
            {
                let data = new FormData()

                data.append('avatar', avatar)
                
                await axios.post(`/api/users/${this.user.name}/avatar`, data)

                flash('Avatar uploaded')
            },
        },

        computed:
        {
            canUpdate()
            {
                return this.authorize(user => user.id === this.user.id)
            },
        },
    }
</script>