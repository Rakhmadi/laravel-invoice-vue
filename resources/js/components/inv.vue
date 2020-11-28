<template>
    <div>
        <v-card class="ma-3">
            <v-card-title primary-title>
                Create Invoices
            </v-card-title>
            <v-card-text>
                 <v-autocomplete
                   label="Customer"
                   :items="datax"
                   item-text="name"
                   item-value="id"
                   v-model="selecteda"
               ></v-autocomplete>
               <v-btn elevation-11 color="primary"  @click="makeinv()">Create Invoice</v-btn>
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
           :items="datas"
           :items-per-page="10"
           :search="search"
           class="elevation-5"
        >
        <template v-slot:item="datas">
            <tr>
            <td>{{datas.item.name}}</td>
            <td>{{datas.item.phone}}</td>
            <td>{{datas.item.address}}</td>
            <td>{{datas.item.email}}</td>
            <td class="font-weight-medium">{{datas.item.code_invoice}}</td>

            <td>
                <v-btn @click="deleteinv(datas.item.invoices_id)" small  fab class="mx-2" color="error">
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                <v-btn @click="look(datas.item.invoices_id)" small  fab class="mx-2" color="primary">
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
        await this.getdatas()
        await this.getinvdata()
    },
    data(){
        return{
            datax:[],
            selecteda:'',
            id_inv:'',
            datas:[],
            search:'',
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
                    text:"Code Invoices",
                    value:"email"
                },
                {
                    text:"Action",

                },
            ]
        }
    },
    methods:{
         async getinvdata(){
        const resp = await fetch(`api/invoice/getinvoiceslist?token=${this.$root.token()}`)
        .then(_=>(_.json()))
        this.datas = resp
        console.log(this.datax);

          },
        async getdatas(){
        const resp = await fetch(`api/customer?token=${this.$root.token()}`)
        .then(_=>(_.json()))
        this.datax = resp
        console.log(this.datax);

          },
          async makeinv(){
              const resp = await fetch(`/api/invoice/make?token=${this.$root.token()}&customers_id=${this.selecteda}`,{
                method:'POST',
              }).then(_=>(_.json().then(d=>({status:_.status,body:d}))))
              if (resp.status == 200) {
                  this.$root.notifSucces('','Success');
                  this.$router.push({
                      name:'dinv',
                      params:{
                          id:resp.body.id_invoice
                      }
                  })
              }else if(resp.status == 400){
                  for (const key in resp.body) {
                    this.$root.notifWarning(resp.body[key][0],'')
                }
              }

          },
          async deleteinv(id){
               const resp = await fetch(`api/invoice/deleteinv/${id}?token=${this.$root.token()}`,{
                  method:'DELETE'
              })
              .then(_=>(_.json().then(d=>({status:_.status,body:d}))))
              if (resp.status == 200) {
                  this.$root.notifSucces('','Success')
                  await this.getinvdata()
              }
          },
          async look(id){
               this.$router.push({
                      name:'dinv',
                      params:{
                          id:id
                      }
                  })
          }
    }
}
</script>

