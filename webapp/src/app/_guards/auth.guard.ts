import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';

@Injectable({ providedIn: 'root' })
export class AuthGuard implements CanActivate {

   

    constructor(private router: Router) {}

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {

        var user = JSON.parse(localStorage.getItem("currentUser"));
        
        if (localStorage.getItem("currentUser")) { // logged in 

            var roles = route && route.data["roles"];
            var perfiles = route && route.data["perfiles"];

            
            if(roles != null){                
                if(user.tipo == roles){
                    var respuesta = true;
                    if(user.tipo == 2){
                        respuesta = false;
                        for (let index = 0; index < perfiles.length; index++) {
                            if(perfiles[index] == user.perfil) respuesta = true;
                        }
                        
                    }
                    return respuesta;
                }else{
                    this.router.navigate(["/login"]);
                    return false;
                }
            }else{
                return true;
            }

        
        
          
            
           

            
        }

        // not logged in so redirect to login page with the return url
        this.router.navigate(["/login"], { queryParams: { returnUrl: state.url }});
        return false;
    }




}