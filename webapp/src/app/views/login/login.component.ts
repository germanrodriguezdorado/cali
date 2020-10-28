import { Component, OnInit, ElementRef, ViewChild } from '@angular/core';
import { Router, ActivatedRoute } from "@angular/router";
import { first } from "rxjs/operators";
import { AuthService } from "@services/auth.service";
import {FormBuilder, FormControl, FormGroup, Validators} from '@angular/forms';

@Component({
  selector: 'app-dashboard',
  templateUrl: 'login.component.html'
})
export class LoginComponent implements OnInit{ 

  returnUrl: string; 
  loginForm: FormGroup;
  loading: boolean;
  error_message: boolean;
  @ViewChild("inputUsuario", { static: true }) inputUsuario:ElementRef;




  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private AuthService: AuthService,
    private FormBuilder: FormBuilder,         
) {

    

}







  ngOnInit() {
    this.loading = false;  
    this.error_message = false;          
    this.AuthService.logout(); 
    this.returnUrl = this.route.snapshot.queryParams["returnUrl"];

    this.loginForm = this.FormBuilder.group({
      'username': new FormControl('', Validators.required),
      'password': new FormControl('', Validators.required),
    });



    this.inputUsuario.nativeElement.focus();
  }








  submit() {

        

   
    this.loading = true; 
    
    
    this.AuthService.login(this.loginForm.controls['username'].value, this.loginForm.controls['password'].value)
        .pipe(first())
        .subscribe(
            data => {
               
                if(data["user_id"] == ""){ // Mal login    
                    this.inputUsuario.nativeElement.focus(); 
                    this.loading = false;   
                    this.error_message = true;              
                }else{

                    // Si hay ruta de retorno, lo redirijo. Sino voy para el home de cada tipo de user
                    
                    if(this.returnUrl != null && this.returnUrl != "/"){
                        this.router.navigate([this.returnUrl]); 
                    }else{
                      if(data["tipo"] == "1") this.router.navigate(["negocio/informacion"]);
                      if(data["tipo"] == "2") this.router.navigate(["cliente/informacion"]);
                    }
                }                   
            });

            return;    
}



keyDownFunction(event) {
  if (event.keyCode === 13 && this.loginForm.valid) {
    this.submit();
  }
}


}
