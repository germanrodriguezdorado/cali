import { Component, OnInit, ViewChild, ElementRef } from "@angular/core";
import { Router, ActivatedRoute } from "@angular/router";
import { AuthService } from "@services/auth.service";
import { ToastService } from "@services/toast.service";
import { Utilidades } from "@helpers/utilidades";
import { BsModalService, BsModalRef } from "ngx-bootstrap/modal";
import { NgxSpinnerService } from 'ngx-spinner';


@Component({
    selector: 'app-dashboard',
    templateUrl: "passwordRecoveryS1.component.html"
})


export class PasswordRecoveryS1Component implements OnInit {

   

    email: string;
    fin: boolean = false;
    loading: boolean;
   
    constructor(
        private route: ActivatedRoute,
        private router: Router,
        private AuthService: AuthService,   
        private toastService: ToastService, 
        private Utilidades: Utilidades,
        private BsModalService: BsModalService, 
        private NgxSpinnerService: NgxSpinnerService           
    ) {


      
        

    }

    ngOnInit() {            
        
     this.loading = false;

    }




    passwordRecovery(){

      this.loading = true;
      this.AuthService.passwordResetStep1(this.email).subscribe(response => {
        this.loading = false;
        if(response){
         this.fin = true;
        }else{
          this.toastService.notificar("error", "Ha ocurrido un error", []);
        }
      });

    }









    


    
}