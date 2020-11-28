const headers ={
    'Accept': 'application/json',
    'Content-Type': 'application/json'
}
const  AuthLogin = async(data)=>{
    const resp = await fetch('/api/login',{
        method:'POST',
        headers,
        body:JSON.stringify(data)
    }).then( _ =>(  _.json().then(data => ({status: _.status, body: data})) ));
    localStorage.setItem("token",resp.body.token)
    return resp
}

const AuthRegister = async(data)=>{
    const resp = await fetch('/api/register',{
        method:'POST',
        headers,
        body:data
    }).then(_ =>(_.json() ));
    localStorage.setItem("token",resp.token)
    return resp
}

const AuthLogout = async()=>{
    var token = {
        "token":localStorage.getItem("token")
    }
    const resp = await fetch('/api/logout',{
        method:'GET',
        headers,
        body:JSON.stringify(token)
    })
    localStorage.removeItem("token")
    return resp
}
const AuthToken = async()=>{
    return localStorage.getItem("token");
}
const Authcek = async()=>{
    var token = {
        "token":localStorage.getItem("token")
    }
    const resp = await fetch('/api/cektoken',{
        method: 'POST',
        headers,
        body:JSON.stringify(token)
    }).then(_=>(_.json().then(data => ({status: _.status, body: data}))));
    return  resp
}
export {AuthLogin,AuthRegister,AuthLogout,AuthToken,Authcek}
