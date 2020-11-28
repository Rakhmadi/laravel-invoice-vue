<template>
  <v-app color="#1f4068" dark id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
      color="#fff"
      v-show="d"
    >
      <Drawer></Drawer>
    </v-navigation-drawer>

    <v-app-bar color="#56556e" dark app>
      <v-app-bar-nav-icon v-show="d" @click="drawer = !drawer"><v-icon>mdi-menu</v-icon></v-app-bar-nav-icon>
      <v-toolbar-title>Application Invoice</v-toolbar-title>
    </v-app-bar>

    <v-main  >
      <v-container>
         <router-view></router-view>
          <vue-snotify></vue-snotify>
      </v-container>
    </v-main>
  </v-app>
</template>

<script>
import Drawer from './drawer'
  export default {
      components:{
          Drawer
      },
      async beforeMount(){
          this.$root.Authcek_()
      },
      async created() {

      },
    data: () => ({
        drawer: null,
        d:true

        }),
  }
</script>
