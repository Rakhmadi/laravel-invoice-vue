<template>
    <div>
        <v-row>
            <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                    <v-card-title primary-title>
                        Total Invoices
                    </v-card-title>
                    <v-card-text>
                        <h2>{{response.total_invoices}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
             <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                     <v-card-title primary-title>
                        Total Customer
                    </v-card-title>
                    <v-card-text>
                        <h2> {{response.total_customers}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
             <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                     <v-card-title primary-title>
                        Total Product
                    </v-card-title>
                    <v-card-text>
                        <h2>{{response.total_products}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
             <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                     <v-card-title primary-title>
                        Average Transaction  Perday
                    </v-card-title>
                    <v-card-text>
                        <h2> {{parseInt(response.average_day)}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                     <v-card-title primary-title>
                       Average Product Price
                    </v-card-title>
                    <v-card-text>
                        <h2>Rp {{cur}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="4">
                <v-card  class="f" elevation="10">
                     <v-card-title primary-title>
                         Average Total Transaction Qty
                    </v-card-title>
                    <v-card-text>
                        <h2> {{Number(response.average_cutomers_total_qty)}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col cols="12" sm="6">
                <v-card  class=" elevation-10">
            <v-card-title primary-title>
                Invoices Statistic
            </v-card-title>
            <v-card-text>
                <canvas style="heigth:300px !important" id="my-chart"></canvas>
            </v-card-text>
        </v-card>

            </v-col>
             <v-col cols="12" sm="6">
                  <v-card class=" elevation-5">
                      <v-card-title primary-title>
                          Invoice
                      </v-card-title>
                      <v-card-text>
                           <v-data-table

           loading-text="Loading... Please wait"
           :headers="heade"
           :items="datas"
           :items-per-page="4"
           :search="search"
           dense

        >
        <template v-slot:item="datas">
            <tr>
            <td>{{datas.item.name}}</td>
            <td class="font-weight-medium">{{datas.item.code_invoice}}</td>

            <td>
                <v-btn @click="look(datas.item.invoices_id)" small  fab class="mx-2" elevation="0" color="transparent">
                    <v-icon>mdi-pencil</v-icon>
                </v-btn>
            </td>
          </tr>
        </template>

        </v-data-table>
                      </v-card-text>
                  </v-card>
             </v-col>
        </v-row>

    </div>
</template>

<script>
    import Chart from 'chart.js';
    export default {
        async mounted() {
            await this.$root.Authcek_()
            await this.getinvdata()
            var token = {
                'token':localStorage.getItem('token')
            }
            this.response = await fetch('/api/invoice/datadash',{
                method: 'post',
                headers:{
                     'Accept': 'application/json',
                     'Content-Type': 'application/json'
                   },
                body:JSON.stringify(token)
            }).then(_=>_.json())
            this.date =this.response.stat_invoice.map(list=>{
                return list.date
            })
            this.total = this.response.stat_invoice.map(list=>{
                return list.total
            })
            new Chart(document.getElementById('my-chart'), {
  type: 'line',
  data: {
    labels: this.date,
    datasets: [
      {
        label: 'Invoices',
        data: this.total,
        backgroundColor: ['#ffd571',],
        borderColor: ['#ffc847',],

      }
    ],

  },options: {
        scales : {
            yAxes : [{
                ticks : {
                    beginAtZero : true
                }
            }]
        }
    }
});
            console.log(this.response);
            this.cur=this.toRp(parseInt(this.response.average_product_price))
        },
        data() {
            return {
                response:[],
                cur:'',
                date:'',
                total:'',
                datas:[],
                heade:[
                 {
                    text:"Name",
                    value:"name"
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
        methods: {
             async getinvdata(){
        const resp = await fetch(`api/invoice/getinvoiceslist?token=${this.$root.token()}`)
        .then(_=>(_.json()))
        this.datas = resp
        if (this.datas.msg == "Unauthorized.") {
            this.$router.push({ path:'/login'})
        }
        console.log(this.datas)
        },
            toRp(bilangan){
                var	number_string = bilangan.toString(),
	            sisa 	= number_string.length % 3,
	            rupiah 	= number_string.substr(0, sisa),
	            ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                	var separator = sisa ? '.' : '';
                	return rupiah += separator + ribuan.join('.');
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
        },
    }
</script>
<style lang="css">
    .f{
         border-left: 5px solid #ffd571 !important
    }
</style>
