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
                        <h2> {{response.average_day}}</h2>
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
                      Average Transaction Qty
                    </v-card-title>
                    <v-card-text>
                        <h2> {{Number(response.average_cutomers_total_qty)}}</h2>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    export default {
        async mounted() {
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
            console.log(this.response);
            this.cur=this.toRp(parseInt(this.response.average_product_price))
        },
        data() {
            return {
                response:[],
                cur:''
            }
        },
        methods: {
            toRp(bilangan){
                var	number_string = bilangan.toString(),
	            sisa 	= number_string.length % 3,
	            rupiah 	= number_string.substr(0, sisa),
	            ribuan 	= number_string.substr(sisa).match(/\d{3}/g);

                if (ribuan) {
                	var separator = sisa ? '.' : '';
                	return rupiah += separator + ribuan.join('.');
                }
            }
        },
    }
</script>
<style lang="css">
    .f{
         border-left: 5px solid #ffd571 !important
    }
</style>
