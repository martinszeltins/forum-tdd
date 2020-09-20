<template>
    <div v-if="notifications.length" class="dropdown">
        <button
            class="btn btn-default dropdown-toggle"
            type="button"
            id="dropdownMenuButton"
            data-toggle="dropdown"
            aria-haspopup="true"
            aria-expanded="false">

            <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-bell" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2z"/>
                <path fill-rule="evenodd" d="M8 1.918l-.797.161A4.002 4.002 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4.002 4.002 0 0 0-3.203-3.92L8 1.917zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5.002 5.002 0 0 1 13 6c0 .88.32 4.2 1.22 6z"/>
            </svg>
        </button>

        <div
            class="dropdown-menu"
            aria-labelledby="dropdownMenuButton">

            <a
                v-for="notification in notifications"
                @click="markAsRead(notification)"
                class="dropdown-item"
                :href="notification.data.link">
                {{ notification.data.message }}
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        data()
        {
            return {
                notifications: [],
            }
        },

        methods:
        {
            async fetchUnreadNotifications()
            {
                const result = await axios.get(`/profiles/${this.username}/notifications`)
                this.notifications = result.data
            },

            markAsRead(notification)
            {
                axios.delete(`/profiles/${this.username}/notifications/${notification.id}`)
            },
        },

        created()
        {
            this.fetchUnreadNotifications()
        },

        computed:
        {
            username()
            {
                return window.App.user.name
            },
        },
    }
</script>