<template>
    <div>
        <!-- SOURCE -->
    <div id="printMe">
        <center><h1>Invoice</h1></center>
        <table  width="100%">
            <tr>
                <td>
                        name : {{datainv.invoice.name}}
                        <br>
                        email : {{datainv.invoice.email}}
                        <br>
                        address : {{datainv.invoice.address}}
                        <br>
                        phone : {{datainv.invoice.phone}}
                </td>
                <td>
                   <br>Invoice code : {{datainv.invoice.codeinv}}
                   <br>Invoice date : {{datainv.invoice.created_at}}
            </td>
            </tr >
        </table> <v-simple-table>
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
                                 </tr>
                             </thead>
                             <tbody>
                                 <tr v-for="item in datainv.detail" v-bind:key="item.id">
                                     <td>{{item.title}}</td>
                                     <td>{{item.discription}}</td>
                                     <td>Rp. {{item.price}}</td>
                                     <td>{{item.qty}}</td>
                                  </tr>
                             </tbody>
                         </template>
                     </v-simple-table>

    </div>
    <!-- OUTPUT -->
    <v-btn @click="print" color="primary">Print</v-btn>

   </div>
</template>
<script>
    
export default {
    async mounted(){
        console.log("hello");
        await this.getInvdata();
        await this.print()
    },
  data() {
    return {

      output: null,
      datainv:[],
    }
  },
  methods: {
    print() {
      var printContents = document.getElementById("printMe").innerHTML;
     var originalContents = document.body.innerHTML;
     document.body.innerHTML = printContents;
     window.print();

    },
    async getInvdata(){
            const resp = await fetch(`api/invoice/getInv/${this.$route.params.id}?token=${this.$root.token()}`)
            .then(_=>(_.json().then(d=>({status:_.status,body:d}))))
            this.datainv = resp.body
            console.log(resp);

        },
  }
}
</script>
<style lang="css">

@media print {
  body {
    overflow: auto;
    height: auto;
  }
}
</style>
