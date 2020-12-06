
<template>
<div>

      <v-list-item>
        <v-list-item-content>
          <v-list-item-title class="title">
            {{name}}
          </v-list-item-title>
          <v-list-item-subtitle>
            {{email}}
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list dense nav>
        <v-list-item
          v-for="item in items"
          :key="item.title"
          link
          v-bind:to="item.link"
        >
          <v-list-item-icon>
            <v-icon>{{ item.icon }}</v-icon>
          </v-list-item-icon>

          <v-list-item-content>
            <v-list-item-title>{{ item.title }}</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </v-list>
</div>
</template>

<script>
  export default {
    created() {
       setInterval(()=>{
            var s = localStorage.getItem("name")
            var g = localStorage.getItem("email")
            this.email = g
            this.name = s
       },1000);
    },
    data () {
      return {
        items: [
          { title: 'Dashboard', icon: 'mdi-view-dashboard', link:'/' },
          { title: 'Invoices', icon: 'mdi-note-text',link:'/invoice' },
          { title: 'Products', icon: 'mdi-format-list-bulleted',link:'/product' },
          { title: 'Customers', icon: 'mdi-account',link:'/customer','click':'lg' },

          { title: 'About', icon: 'mdi-help-box',link:'/n' },
        ],
        right: null,
        name:'',
        email:''
      }
    },
    mounted(){
        // var n = this
        // n.email =localStorage.getItem(email)
        // n.name = localStorage.getItem(name)

    },methods: {

    },
  }
</script>
