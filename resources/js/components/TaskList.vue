<template>
    <div>
        <ul>
            <li v-for="task in tasks" v-text="task"></li>
        </ul>

        <input type="text" v-model="newTask" class="input-group-text" @blur="addTask">
    </div>
</template>

<script>
    export default {
        data() {
            return {
                tasks: [],
                newTask: ''
            }
        },

        created() {
            axios.get('/tasks').then(response => (this.tasks = response.data));

            window.Echo.channel('tasks').listen('TaskCreated', e => {
                console.log(e)
                this.tasks.push(e.task.body)
            });
        },

        methods: {
            addTask() {
                if (this.newTask == '') {
                    return false;
                }
                axios.post('/tasks', { body: this.newTask});
                this.tasks.push(this.newTask);
                this.newTask = '';
            }
        }
    }
</script>
