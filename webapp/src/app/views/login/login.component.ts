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
  @ViewChild("inputUsuario", { static: true }) inputUsuario:ElementRef;




  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private AuthService: AuthService,
    private FormBuilder: FormBuilder,         
) {

    

}







  ngOnInit() {            
    this.AuthService.logout();    
    this.returnUrl = this.route.snapshot.queryParams["returnUrl"];

    this.loginForm = this.FormBuilder.group({
      'username': new FormControl('', Validators.required),
      'password': new FormControl('', Validators.required),
    });



    this.inputUsuario.nativeElement.focus();
  }






  submit(model) {

        

   

    this.AuthService.login(this.loginForm.controls['username'].value, this.loginForm.controls['password'].value)
        .pipe(first())
        .subscribe(
            data => {
               
                if(data["user_id"] == ""){ // Mal login    
                    this.inputUsuario.nativeElement.focus();                
                }else{

                    // Si hay ruta de retorno, lo redirijo. Sino voy para el home de cada tipo de user
                    
                    if(this.returnUrl != null && this.returnUrl != "/"){
                        this.router.navigate([this.returnUrl]); 
                    }else{
                        this.router.navigate([""]);
                    }
                }                   
            });

            return;    
}


}
