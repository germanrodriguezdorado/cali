import { Component, OnInit } from '@angular/core';
import { AuthService } from "@services/auth.service";
import { ToastService } from "@services/toast.service";
import { Utilidades } from "@helpers/utilidades";

@Component({
  selector: 'app-dashboard',
  templateUrl: 'register.component.html'
})
export class RegisterComponent implements OnInit{

  nombre: string;
  email: string;
  password1: string;
  password2: string;
  paso: number;
  cargando: boolean;

  constructor(
    private AuthService: AuthService,
    private ToastService: ToastService,
    private Utilidades: Utilidades
  ) { }



  ngOnInit() {
    this.nombre = "";
    this.email = "";
    this.password1 = "";
    this.password2 = "";
    this.paso = 1;
    this.cargando = false;
  }




  registro(){

    if(
      this.nombre == "" ||
      this.email == "" ||
      this.password1 == "" ||
      this.password2 == ""
    ){
      this.ToastService.notificar("warning", "Por favor, complete todos los datos requeridos.", []);
      return;
    }


    if(!this.Utilidades.validarEmail(this.email)){
      this.ToastService.notificar("warning", "Por favor, ingrese un e-mail correcto.", []);
      return;
    }


    if(this.password1 != this.password2){
      this.ToastService.notificar("warning", "Por favor, verifique que las 2 contraseñas sean iguales.", []);
      return;
    }

    this.cargando = true;

    var data = {
      "nombre": this.nombre,
      "email": this.email,
      "password": this.password1
    }



    this.AuthService.registerStep1(data).subscribe(respuesta => {

      this.cargando = false;

      if(respuesta == "error"){
        this.ToastService.notificar("error", "Ha ocurrido un error", []);
        return;
      }

      if(respuesta == "email_existente"){
        this.ToastService.notificar("warning", "Ya existe un usuario con esa cuenta", []);
        return;
      }

      if(respuesta == "1"){
        this.paso = 2;
      }

    });

  }


  

}
