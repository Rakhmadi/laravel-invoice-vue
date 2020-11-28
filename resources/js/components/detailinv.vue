<template>
    <div>
        <v-card>
            <v-card-title primary-title>
                Invoice Detail
            </v-card-title>
            <v-card-text>
                <v-row>
                    <v-col cols="12" sm="6">
                        name : {{datainv.invoice.name}}
                        <br>
                        email : {{datainv.invoice.email}}
                        <br>
                        address : {{datainv.invoice.address}}
                        <br>
                        phone : {{datainv.invoice.phone}}
                    </v-col>
                </v-row>

                     <v-simple-table>
                         <template v-slot:default>
                             <thead>
                                 <tr>
                                      <th class="text-left">
                                         Name Product
                                      </th>
                                       <th class="text-left">
                                         Discription
                                      </th>
                                      <th class="text-left">
                                         Price
                                      </th>
                                      <th class="text-left">
                                         Qty
                                      </th>
                                      <th class="text-left">
                                         Action
                                      </th>
                                 </tr>
                             </thead>
                             <tbody>
                                 <tr v-for="item in datainv.detail">
                                     <td>{{item.title}}</td>
                                     <td>{{item.discription}}</td>
                                     <td>Rp. {{item.price}}</td>
                                     <td>{{item.qty}}</td>
                                     <td><v-btn @click="del(item.details_id,item.qty,item.id)" fab small color="error"><v-icon>mdi-delete</v-icon></v-btn></td>

                                  </tr>
                                  <tr>
                                      <td><v-autocomplete
                                           label=""
                                           :items="dataresp"
                                           item-text="title"
                                           item-value="id"
                                           v-model="selecteda"
                                       ></v-autocomplete></td>
                                      <td></td>
                                      <td></td>
                                      <td><v-text-field
                                          label=""
                                          name="qty"
                                          id="qty"
                                          v-model="qty"
                                          type="number"
                                      ></v-text-field></td>
                                      <td><v-btn @click="createDetail()" fab small color="primary"><v-icon>mdi-plus</v-icon></v-btn></td>
                                  </tr>
                             </tbody>
                         </template>
                     </v-simple-table>

            </v-card-text>
        </v-card>
    </div>
</template>
<script>
export default {
    async mounted() {
        console.log(this.$route.params)
        await this.getInvdata()
        await this.prdk()
    },
    data(){
        return{
            datainv:{
                invoice:[],
                detail:[]

            },
            dataresp:[],
            selecteda:'',
            qty:'',

        }
    },
    methods: {
        async getInvdata(){
            const resp = await fetch(`api/invoice/getInv/${this.$route.params.id}?token=${this.$root.token()}`)
            .then(_=>(_.json().then(d=>({status:_.status,body:d}))))
            this.datainv = resp.body
            console.log(resp);

        },
        async prdk(){
             const resp = await fetch(`api/product?token=${this.$root.token()}`,{
               method:'GET'
           }).then(_=>(_.json()))
           this.dataresp = resp
        },
        async createDetail(){
            let data ={
                 product_id: this.selecteda,
                 invoice_id: this.$route.params.id,
                 qty:this.qty
            }
            const resp = await fetch(`/api/invoice/make_details/${this.$route.params.id}?token=${this.$root.token()}`,{
               method:'POST',
               headers:{
                   'Accept': 'application/json',
                   'Content-Type': 'application/json'
               },
               body:JSON.stringify(data)
            })
            .then(_=>(_.json().then(d=>({status:_.status,body:d}))))
            if(resp.status == 200){
                this.$root.notifSucces('','Success');
                await this.getInvdata()
                await this.prdk()
            }else if(resp.status == 400){
                 for (const key in resp.body) {
                    this.$root.notifWarning(resp.body[key][0],'')
                    console.log(this.selecteda);
                }
            }
        },
        async del(id,qty,prdk_id){
            const data = await fetch(`api/invoice/deletedetai/${id}/${this.$route.params.id}/${qty}/${prdk_id}?token=${this.$root.token()}`,{
                method:'DELETE'
            })
            .then(_=>(_.json()))
            if (data.msg == "Success") {
                this.$root.notifSucces('','Success');
                await this.getInvdata()
                await this.prdk()
            }
        }
    },

}
</script>
