<template>
    <div>
        <h1>{{this.name}}</h1>
        <ul>
            <li v-for="message in messages" v-text="message"></li>
        </ul>

        <input type="text" v-model="newMessage" class="input-group-text" @blur="addMessage">
    </div>
</template>

<script>
export default {
    props: {
        id: {
            required: true,
        },
        name: {
            required: true,
        }
    },
    data() {
        return {
            messages: [],
            newMessage: ''
        }
    },

    created() {
        console.log(this.id)
        console.log(this.name)
        axios.get('/api/rooms/' + this.id).then((response) => {
            console.log(response)
            this.messages = response.data
        })

        window.Echo.channel('room.' + this.id).listen('MessageCreated', e => {
            console.log(666, e)
            this.messages.push(e.message.body)
        });
    },

    methods: {
        addMessage() {
            if (this.newMessage == '') {
                return false;
            }
            axios.post('/api/rooms/' + this.id +'/messages', { body: this.newMessage} ).then((response) => {
                console.log(response)
                this.messages.push(this.newMessage);
                this.newMessage = '';
            })
        }
    }
}
</script>
