//import polices.js yang kita buat 
import policies from './policies';


export default{
    install(vue, options){
        //define function yang bisa dipanggil kaya template
        Vue.prototype.authorize = function(policy, model){
        //buat cek udh login apa belum
            if(!window.Auth.signedIn) return false;

            //method name in the first argument is string sama make sure model nya itu object
            if(typeof policy === 'string' && typeof model === 'object'){
                //ini kita buat di app.blade.php (layout)
                const user = window.Auth.user;

                //authorize('modify', answer)
                return policies[policy](user, model);
                // similar with return policies.modify(user, model);
            }
        };
        Vue.prototype.signedIn = window.Auth.signedIn;
    }
}