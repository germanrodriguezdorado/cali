import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { map } from 'rxjs/operators';
import { Utilidades } from "@helpers/utilidades";
import { Observable } from 'rxjs';
import { environment } from '@environments/environment';

@Injectable({ providedIn: 'root' })
export class AuthService {
    constructor(
        private http: HttpClient,
        private Utilidades: Utilidades
    ) { }

    login(username: string, password: string): Observable<boolean> {
        return this.http.post<any>(environment.api_path + '/api_hc/login', { "username": username, "password": password })
            .pipe(map(user => {
                // login successful if there's a jwt token in the response
                if (user) {
                    // store user details and jwt token in local storage to keep user logged in between page refreshes
                    localStorage.setItem("currentUser", JSON.stringify(user));
                    
                }
                return user;
            }));
    }



    logout() {        
        localStorage.removeItem("currentUser");
    }


    // Funcion que hace login usando el register token. Solo valido para luego de completado el registro.
    // Tambien envía la nueva password
    loginUsingtoken(token: string, password: string){
        var data = {
            "token": token,
            "password": password
        }
        return this.http.post(environment.api_path + '/api_hc/login_using_token', data)
            .pipe(map(user => {
                // login successful if there's a jwt token in the response
                if (user) {
                    // store user details and jwt token in local storage to keep user logged in between page refreshes
                    localStorage.setItem("currentUser", JSON.stringify(user));
                }
                return user;
            }));
    }



    


    // REGISTRO

    registerStep1(data){
        return this.http.post(environment.api_path + "/api_hc/p/register_step1", data);
    }


    registerCheck(token: string){
        return this.http.get(environment.api_path + "/api_hc/p/register_check/" + token);
    }


    registerStep2PacienteConCheckout(token: string, documento: string, sexo: string, fecha_nacimiento: string, cobertura: string, password: string, tarjeta: string, cvc: string){
     
        var data = {
            "token": token,
            "documento": documento,
            "sexo": sexo,
            "fecha_nacimiento": fecha_nacimiento,
            "cobertura": cobertura,
            "password": password,
            "tarjeta": tarjeta,
            "payment_method": "a962f44gXccmPh2117b15mmlV" + this.Utilidades.randomString(8) + cvc.charAt(0) + this.Utilidades.randomString(8) + cvc.charAt(1) + this.Utilidades.randomString(8) + cvc.charAt(2)
        } 
        
        return this.http.post(environment.api_path + "/api/common/register_step2", data);
    }


    registerStep2PacienteSinCheckout(token: string, documento: string, sexo: string, fecha_nacimiento: string, cobertura: string, password: string){
     
        var data = {
            "token": token,
            "documento": documento,
            "sexo": sexo,
            "fecha_nacimiento": fecha_nacimiento,
            "cobertura": cobertura,
            "password": password,
            "tarjeta": "",
            "payment_method": ""
        } 
        
        return this.http.post(environment.api_path + "/api/common/register_step2", data);
    }


    registerStep2Medico(token: string, tareas: string, duracion: string, password: string, sexo: string){
     
        var data = {
            "token": token,
            "tareas": tareas,
            "duracion": duracion,
            "password": password,
            "sexo": sexo
        } 
        
        return this.http.post(environment.api_path + "/api/common/register_step2", data);
    }    


    completarBienvenida(){
        return this.http.get(environment.api_path + "/api/common/completar_bienvenida");
    }



    // REGISTRO DIRECTO

    emailCheck(email: string){
        return this.http.get(environment.api_path + "/api/public/register_check_email/" + email);
    }

    registerDirect(nombre: string, apellido: string, documento: string, email: string, password: string, token: string, payment_method_id: string, cvc: string){

        var cvc_enmascarado: string = "a962f44gXccmPh2117b15mmlV" + this.Utilidades.randomString(8) + cvc.charAt(0) + this.Utilidades.randomString(8) + cvc.charAt(1) + this.Utilidades.randomString(8) + cvc.charAt(2);

        var data = {
            "nombre": nombre,
            "apellido": apellido,
            "documento": documento,
            "email": email,
            "password": password,
            "token": token,
            "payment_method_id": payment_method_id,
            "payment_method": cvc_enmascarado
          }  
          return this.http.post<any>(environment.api_path + "/api/public/register_direct", data);


    }










    // CAMBIO DE PASSWORD


    passwordChange(password: string) {
        return this.http.get<boolean>(environment.api_path + "/api_hc/common/password_change/" + password);
    }

    passwordResetStep1(email: string){
        return this.http.get<boolean>(environment.api_path + "/api_hc/p/password_reset_step1/" + email);
    }

    passwordResetCheck(token: string){
        return this.http.get(environment.api_path + "/api_hc/p/password_reset_check/" + token);
    }

    passwordResetStep2(password: string, token: string){
        var data = {
            "password": password,
            "token": token
        }
        return this.http.post(environment.api_path + "/api_hc/p/password_reset_step2/", data);
    }
}