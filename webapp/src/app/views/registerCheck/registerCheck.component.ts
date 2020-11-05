import { Component, OnInit } from '@angular/core';
import { AuthService } from "@services/auth.service";
import { ToastService } from "@services/toast.service";
import { Utilidades } from "@helpers/utilidades";
import { Router, ActivatedRoute } from "@angular/router";

@Component({
  selector: 'app-dashboard',
  templateUrl: 'registerCheck.component.html'
})
export class RegisterCheckComponent implements OnInit{

  mostrar_card: boolean;
  token: string;

  constructor(
    private AuthService: AuthService,
    private ToastService: ToastService,
    private Utilidades: Utilidades,
    private ActivatedRoute: ActivatedRoute,
    private Router: Router,
  ) { }



  ngOnInit() {

    this.mostrar_card = false;
    this.token = "";
    
    this.ActivatedRoute.params.subscribe(params => {
      this.token = params["token"];
    });

    if(this.token == ""){
      this.Router.navigate(["/"]);
    }




    this.AuthService.registerCheck(this.token).subscribe(respuesta => {
      if(respuesta["user_id"] == ""){
        this.Router.navigate(["/"]);
      }else{
        localStorage.setItem("currentUser", JSON.stringify(respuesta));
        this.mostrar_card = true;
      }
    });

  }












  

}
