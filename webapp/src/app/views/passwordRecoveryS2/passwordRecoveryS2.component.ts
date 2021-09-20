import { Component, OnInit, ViewChild, ElementRef } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { AuthService } from "@services/auth.service";
import { ToastService } from "@services/toast.service";
import { Utilidades } from "@helpers/utilidades";
import { BsModalService, BsModalRef } from "ngx-bootstrap/modal";
import { NgxSpinnerService } from 'ngx-spinner';


@Component({
  selector: 'app-dashboard',
  templateUrl: "passwordRecoveryS2.component.html"
})


export class PasswordRecoveryS2Component implements OnInit {

   

    token: string;
    password1: string = "";
    password2: string = "";
    fin: boolean = false;
    loading: boolean = false;

   
    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private AuthService: AuthService,   
        private toastService: ToastService, 
        private Utilidades: Utilidades,
        private NgxSpinnerService: NgxSpinnerService           
    ) {


      
        

    }

    ngOnInit() {         
      
      
      
        
      this.route.params.subscribe(params => {
        this.token = params["token"];
      });


       this.AuthService.passwordResetCheck(this.token).subscribe(response => {
        
        if(response == 0) this.router.navigate(["/login"]);
      });

    }




    cambiarPassword(){

      this.loading = true;
      this.AuthService.passwordResetStep2(this.password1, this.token).subscribe(response => {
        this.loading = false;
        
        if(response == 1){
          this.fin = true;
        }else{
          this.toastService.notificar("error", "Ha ocurrido un error", []);
        }
        
        
      });

    }









    


    
}