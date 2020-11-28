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
           :items="dataresp"
           :items-per-page="10"
           :search="search"
           class="elevation-5"
        >
        <template v-slot:item="dataresp">
            <tr>
            <td>{{dataresp.item.title}}</td>
            <td>{{dataresp.item.discription}}</td>
            <td>Rp.{{dataresp.item.price}}</td>
            <td>{{dataresp.item.stock}}</td>
            <td>
                <v-btn v-on:click="deleted(dataresp.item.id)" small  fab class="mx-2" color="error">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                 <v-btn v-on:click="update(dataresp.item.id)" small  fab class="mx-2" color="primary">
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
        await this.getdata()
    },
   data() {
       return {
           search:'',
           	title:'',
	        discription:'',
	        price:'',
            stock:'',
            err:'',
            dataresp:[],
            heade:[
                {
                    text:"Title",
                    value:"title"
                },
                {
                    text:"Discription",
                    value:"discription"
                },
                {
                    text:"Price",
                    value:"price"
                },
                {
                    text:"Stock",
                    value:"stock"
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

               token:this.$root.token(),
               title:this.title,
	           discription:this.discription,
	           price:this.price,
	           stock:this.stock

           }
           const resp = await fetch('api/product/save',{
               method:'POST',
               headers:{
                   'Accept': 'application/json',
                   'Content-Type': 'application/json'
               },
               body:JSON.stringify(data)

           }).then(_=>(_.json().then(d=>({status:_.status,body:d}))))
           if (resp.status == 200) {

               this.$root.notifSucces('Success','Message')
                 this.getdata()

           }else if(resp.status == 400){
                this.err = resp.body
                for (const key in this.err) {
                    this.$root.notifWarning(this.err[key][0],'')
                }

           }else{
               this.$root.notifWarning('Failed','Message')
           }

       },
       async getdata(){
           const resp = await fetch(`api/product?token=${this.$root.token()}`,{
               method:'GET'
           }).then(_=>(_.json()))
           this.dataresp = resp

       },
       async deleted(id){
           const resp = await fetch(`api/product/delete/${id}/?token=${this.$root.token()}`,{
               method:'DELETE'
           })
           .then(_=>(_.json().then(data=>({status:_.status,body:data}))))
           if (resp.status == 200) {
               this.$root.notifSucces('Deleted','Message')
               this.getdata()
           }
       },
       async update(id){
           this.$router.push({
                      name:'Editprdk',
                      params:{
                          id:id
                      }
                  })
       }
   },

}
</script>
