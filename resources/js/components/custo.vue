<template>
    <div>
        <v-card  class="ma-3 elevation-5">
            <v-card-title primary-title>
                Customer
            </v-card-title>
            <v-card-text>

                    <v-form ref="form">
                        <v-row>
                           <v-col  cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="name"
                                label="Name"
                                id="name"
                                single-line
                                hide-details
                                v-model="name"
                                required
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="phone"
                                label="Phone"
                                id="phone"
                                 single-line
                                hide-details
                                v-model="phone"
                                required
                                type="number"
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="address"
                                label="Address"
                                id="address"
                                single-line
                                hide-details
                                v-model="address"
                                required
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="email"
                                label="Email"
                                id="email"
                                v-model="email"
                                required
                                single-line
                                hide-details

                               ></v-text-field>
                            </v-col>
                            <v-col cols="12" sm="6">
                                <v-btn @click="save()" color="#56556e" dark>Create</v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
            </v-card-text>
        </v-card>
         <v-card class="ma-3 elevation-5" >
            <v-card-title primary-title>
                <v-text-field
                solo
                 v-model="search"
                 append-icon="mdi-magnify"
                 label="Search"
                 single-line
                 hide-details
                ></v-text-field>
            </v-card-title>
            <v-card-text>
                 <v-data-table

           loading-text="Loading... Please wait"
           :headers="heade"
           :items="datax"
           :items-per-page="10"
           :search="search"
           class="elevation-5"
        >
        <template v-slot:item="datax">
            <tr>
            <td>{{datax.item.name}}</td>
            <td>{{datax.item.phone}}</td>
            <td>{{datax.item.address}}</td>
            <td>{{datax.item.email}}</td>
            <td>
                <v-btn v-on:click="del(datax.item.id)" small  fab class="mx-2" color="error">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                 <v-btn v-on:click="update(datax.item.id)" small  fab class="mx-2" color="primary">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </td>
          </tr>
        </template>

        </v-data-table>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
export default {
    async mounted() {
        await this.getdatas();
    },
    data(){
        return{
            search:'',
            name: '',
            phone: '',
            address: '',
            email:'',
            datax:[],
            heade:[
                 {
                    text:"Name",
                    value:"name"
                },
                 {
                    text:"Phone",
                    value:"phone"
                }, {
                    text:"Address",
                    value:"address"
                }, {
                    text:"Email",
                    value:"email"
                },
                {
                    text:"Action",

                },
            ]
        }
    },
    methods: {
        async save(){
            let data = {
                 name:this.name,
                 phone: this.phone,
                 address: this.address,
                 email:this.email
            }
            const resp = await fetch(`api/customer/save?token=${this.$root.token()}`,{
                method:'POST',
                headers:{
                    'Accept':'aplication/json',
                    'Content-type':'aplication/json'
                },
                body:JSON.stringify(data)
            }).then(_=>(_.json().then(d=>({status:_.status,body:d}))))
            if (resp.status == 200) {
                this.$root.notifSucces('','Success')
                await this.getdatas();
            }else if( resp.status == 400){
                for (const key in resp.body) {
                    this.$root.notifWarning(resp.body[key][0],'')
                }
            }

        },
        async getdatas(){
        const resp = await fetch(`api/customer?token=${this.$root.token()}`)
        .then(_=>(_.json()))
        this.datax = resp
        console.log(this.datax);

          },
          async del(id){
              const resp = await fetch(`api/customer/delete/${id}?token=${this.$root.token()}`,{
                  method:'DELETE'
              })
              .then(_=>(_.json().then(d=>({status:_.status,body:d}))))
              if (resp.status == 200) {
                  this.$root.notifSucces('','Success')
                  await this.getdatas()
              }
          },
          async update(id){
           this.$router.push({
                      name:'Editcus',
                      params:{
                          id:id
                      }
                  })
       }

    }

}
</script>
