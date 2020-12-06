<template>
    <div>
               <v-card  class="ma-3 elevation-5">
            <v-card-title primary-title>
                Product
            </v-card-title>
            <v-card-text>

                    <v-form ref="form">
                        <v-row>
                           <v-col  cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="title"
                                label="Title"
                                id="title"
                                single-line
                                hide-details
                                v-model="title"
                                required
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="discription"
                                label="Discription"
                                id="discription"
                                 single-line
                                hide-details
                                v-model="discription"
                                required
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="price"
                                label="Price"
                                type="number"
                                id="price"
                                single-line
                                hide-details
                                v-model="price"
                                required
                               ></v-text-field>
                            </v-col>
                             <v-col cols="12" sm="6">
                               <v-text-field
                                solo
                                clearable
                                name="stock"
                                label="Stock"
                                type="number"
                                id="stock"
                                v-model="stock"
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
            title:'',
            discription:'',
            price:'',
            stock:'',
        }
    },
    methods: {
        async g(){
            const resp = await fetch(`/api/product/get/${this.$route.params.id}?token=${this.$root.token()}`)
            .then(_=>(_.json()))
            this.resp = resp
            this.title =this.resp.title
            this.discription =this.resp.discription
            this.price =this.resp.price
            this.stock =this.resp.stock
        },
        async update(){
             let data = {

               token:this.$root.token(),
               title:this.title,
	           discription:this.discription,
	           price:this.price,
	           stock:this.stock

           }
           const resp = await fetch(`/api/product/update/${this.$route.params.id}`,{
               method:'PUT',
               headers:{
                   'Accept': 'application/json',
                   'Content-Type': 'application/json'
               },
               body:JSON.stringify(data)

           }).then(_=>(_.json().then(d=>({status:_.status,body:d}))))
           if (resp.status == 200) {

               this.$root.notifSucces('Success','Message')
               this.$router.push({ path:'/product'})

           }else if(resp.status == 400){
                this.err = resp.body
                for (const key in this.err) {
                    this.$root.notifWarning(this.err[key][0],'')
                }

           }else{
               this.$root.notifWarning('Failed','Message')
           }
        }
    },
}
</script>
