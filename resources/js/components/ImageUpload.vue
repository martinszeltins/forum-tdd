<template>
    <input
        @change="onChange"
        type="file"
        accept="image/*"
    />
</template>

<script>
    export default {
        methods:
        {
            onChange(event)
            {
                if (!this.hasFiles(event.target.files)) return

                this.readFile(event)
            },

            hasFiles(files)
            {
                return files.length
            },

            readFile(event)
            {
                let reader = new FileReader()

                let file = event.target.files[0]

                reader.readAsDataURL(file)

                reader.onload = event => {
                    this.onFileLoaded(event, file)
                }
            },

            onFileLoaded(event, file)
            {
                this.$emit('loaded', {
                    src: event.target.result,
                    file: file,
                })
            },
        },
    }
</script>