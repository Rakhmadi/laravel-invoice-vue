<template>
    <div>
        <v-row align="center" justify="center">
            <v-col sm="5" >
            <v-card class="pa-2">
            <v-card-title class="display-1" >
                  Login
            </v-card-title>
            <v-card-text>
                <v-text-field
                    name="email"
                    label="Email"
                    type="email"
                    id="email"
                    v-model="email"

                ></v-text-field>
                <v-text-field
                    name="password"
                    label="password"
                    id="password"
                    type="password"
                    v-model="password"
                ></v-text-field>
                <v-btn @click="Login()" dark color="#56556e">Login</v-btn>
            </v-card-text>
        </v-card>
        </v-col>
        </v-row>

    </div>
</template>
<script>
import {AuthLogin} from '../Auth.js'
export default {
    data() {
        return {
            email:'',
            password:''
        }
    },
     methods: {
        async Login(){

             const resp= await AuthLogin({
                'email':this.email,
                'password':this.password
            })

            if (resp.status == 200) {
               localStorage.setItem('name',resp.body.name)
               localStorage.setItem('email',resp.body.email)
               localStorage.setItem('token',resp.body.token)
               this.$snotify.success('Login Success', 'Message', {
                    timeout: 2000,
                    showProgressBar: true,
                    closeOnClick: true,
                    pauseOnHover: true
                  });
                this.$router.push({path:'/'})
            } else {
                alert('fail')
            }
        }
    },
}
</script>
