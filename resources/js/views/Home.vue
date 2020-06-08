<template>
    <div>
        <v-card>
            <v-card-title>
                Test App
                <v-spacer></v-spacer>
                <v-text-field
                    v-model="search"
                    append-icon="mdi-magnify"
                    label="Search"
                    single-line
                    hide-details
                ></v-text-field>
            </v-card-title>
            <v-data-table
                :headers="headers"
                :items="desserts"
                sort-by="calories"
                class="elevation-1"
                :search="search"
            >
                <template v-slot:top>
                    <v-toolbar flat color="white">
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn color="primary" dark class="mb-2" v-on="on" @click.stop="dialog = true">New
                                    Item
                                </v-btn>
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{ formTitle }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-text-field v-model="editedItem.position"
                                                              label="Position"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-text-field v-model="editedItem.office" label="Office"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-text-field v-model="editedItem.age" label="Age"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">

                                                <v-text-field type="date" v-model="editedItem.start_date"
                                                              label="Start date"></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="12" md="12">
                                                <v-text-field v-model="editedItem.salary" type="number" label="Salary"></v-text-field>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>

                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                    <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                    >
                        mdi-pencil
                    </v-icon>
                    <v-icon
                        small
                        @click="deleteItem(item)"
                    >
                        mdi-delete
                    </v-icon>
                </template>
                <template v-slot:no-data>
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                </template>
            </v-data-table>
        </v-card>

    </div>

</template>

<script>
    export default {
        data: () => ({
            dialog: false,
            search: '',
            headers: [
                {
                    text: 'Name',
                    align: 'start',
                    sortable: true,
                    value: 'name',
                },
                {text: 'Position', value: 'position'},
                {text: 'Office', value: 'office'},
                {text: 'Age', value: 'age'},
                {text: 'Start date', value: 'start_date'},
                {text: 'Salary', value: 'salary'},
                {text: 'Actions', value: 'actions', sortable: false},
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                position: '',
                office: '',
                age: '',
                start_date: '',
                salary: '',

            },
            defaultItem: {
                name: '',
                position: '',
                office: '',
                age: '',
                start_date: '',
                salary: '',
            },
        }),

        computed: {
            formTitle() {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            },
        },

        watch: {
            dialog(val) {
                val || this.close()
            },
        },

        created() {
            this.initialize()
        },

        methods: {
            initialize() {
                axios
                    .get('/account', this.editedItem)
                    .then(response => {
                        if (response.status == 200 && response.data) {
                            this.desserts = response.data;
                        }
                    })
                    .catch(error => {
                        console.log(error);
                    });
            },

            editItem(item) {
                this.editedIndex = this.desserts.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true


            },

            deleteItem(item) {
                const index = this.desserts.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
                 axios
                  .delete('/account/'+item.id, {
                  	// data
                  })
                  .then(response => {
                  	console.log(response);
                  	if (response.status == 200 && response.data) {
                  		//
                  	}
                  })
                  .catch(error => {
                  	console.log(error);
                  });

                console.log(item);
            },

            close() {
                this.dialog = false
                this.$nextTick(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1


                })
            },

            save() {
                if (this.editedIndex > -1) {
                    Object.assign(this.desserts[this.editedIndex], this.editedItem)
                    axios
                        .put('/account/'+this.editedItem.id, this.editedItem)
                        .then(response => {
                            if (response.status == 200 && response.data) {
                                this.desserts = response.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                } else {
                    this.desserts.push(this.editedItem)
                    axios
                        .post('/account', this.editedItem)
                        .then(response => {
                            if (response.status == 200 && response.data) {
                                this.desserts = response.data;
                            }
                        })
                        .catch(error => {
                            console.log(error);
                        });
                }
                this.close()
            },
        },
    }
</script>
