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
                                <v-btn @click="update()" color="#56556e" dark>Update</v-btn>
                            </v-col>
                        </v-row>
                    </v-form>
            </v-card-text>
        </v-card>
    </div>
</template>
<script>
export default {
    async mounted(){
        await this.g()
    },
    data(){
        return{
            resp:[],
            name: '',
            phone: '',
            address: '',
            email:'',
            stock:'',
        }
    },
    methods: {
        async g(){
            const resp = await fetch(`api/customer/get/${this.$route.params.id}?token=${this.$root.token()}`)
            .then(_=>(_.json()))
            this.resp = resp
            this.name  =  this.resp.name
            this.phone =  this.resp.phone
            this.address =  this.resp.address
            this.email =  this.resp.email
        },
        async update(){
              let data = {
                 name:this.name,
                 phone: this.phone,
                 address: this.address,
                 email:this.email
            }
            const resp = await fetch(`api/customer/update/${this.$route.params.id}?token=${this.$root.token()}`,{
                method:'PUT',
                headers:{
                    'Accept':'aplication/json',
                    'Content-type':'aplication/json'
                },
                body:JSON.stringify(data)
            }).then(_=>(_.json().then(d=>({status:_.status,body:d}))))
            if (resp.status == 200) {
                this.$root.notifSucces('','Success')
                this.$router.push({
                    path:'/customer'
                })
            }else if( resp.status == 400){
                for (const key in resp.body) {
                    this.$root.notifWarning(resp.body[key][0],'')
                }
            }
        }

    },
}
</script>
