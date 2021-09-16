import { Component, OnInit } from '@angular/core';
import { ToastService } from "@services/toast.service";
import { NgxSpinnerService } from "ngx-spinner";
import { Utilidades } from "@helpers/utilidades";
import { NegocioService } from "@services/negocio.service";



@Component({
  templateUrl: 'informacion.component.html'
})
export class NegocioInformacionComponent implements OnInit {
  usuario;
  nombre: string;
  email: string;
  direccion: string;
  telefono: string
  barrio: string;
  duracion: string;
  cupos: string;
  horarios_disponibles: string[];
  barrios_disponibles: string[];
  desde: string;
  hasta: string;
  descanso: string;
  lunes: boolean;
  martes: boolean;
  miercoles: boolean;
  jueves: boolean;
  viernes: boolean;
  sabado: boolean;
  domingo: boolean;

  
  constructor(
    private ToastService: ToastService,
    private NgxSpinnerService: NgxSpinnerService,
    private NegocioService: NegocioService,
    public Utilidades: Utilidades 
  ) { }

  isCollapsed: boolean = false;
  iconCollapse: string = 'icon-arrow-up';

  collapsed(event: any): void {
    // console.log(event);
  }

  expanded(event: any): void {
    // console.log(event);
  }

  toggleCollapse(): void {
    this.isCollapsed = !this.isCollapsed;
    this.iconCollapse = this.isCollapsed ? 'icon-arrow-down' : 'icon-arrow-up';
  }


  ngOnInit() {

    this.NgxSpinnerService.show();
    this.usuario = JSON.parse(localStorage.getItem("currentUser"));
    console.log(this.usuario)
    this.nombre = "";
    this.email = "";
    this.direccion = "";
    this.telefono = "";
    this.barrio = "";
    this.duracion = "";
    this.horarios_disponibles = this.Utilidades.darHorariosDisponibles();
    this.barrios_disponibles = this.Utilidades.darBarriosDisponibles();
    this.lunes = false;
    this.martes = false;
    this.miercoles = false;
    this.jueves = false;
    this.viernes = false;
    this.sabado = false;
    this.domingo = false;
    this.desde = "";
    this.hasta = "";
    this.descanso = "";
    this.cupos = "";


    this.NegocioService.darInfo().subscribe(data => {
      this.nombre = data["nombre"];
      this.email = data["email"];
      this.direccion = data["direccion"];
      this.telefono = data["telefono"];
      this.barrio = data["barrio"];
      this.duracion = data["duracion"];
      this.lunes = data["lunes"];
      this.martes = data["martes"];
      this.miercoles = data["miercoles"];
      this.jueves = data["jueves"];
      this.viernes = data["viernes"];
      this.sabado = data["sabado"];
      this.domingo = data["domingo"];
      this.desde = data["desde"];
      this.hasta = data["hasta"];
      this.descanso = data["descanso"];
      this.cupos = data["cupos"];
      this.NgxSpinnerService.hide();
    });
  }

  

  guardar(){

    if(
      this.nombre == "" ||
      this.email == "" ||
      this.direccion == "" ||
      this.telefono == ""
    ){
      this.ToastService.notificar("warning", "Por favor, complete nombre, email, dirección y teléfono.", []);
      return;
    }


    if(!this.Utilidades.validarEmail(this.email)){
      this.ToastService.notificar("warning", "Por favor, ingrese un e-mail correcto.", []);
      return;
    }

    if(
      this.cupos == ""
    ){
      this.ToastService.notificar("warning", "Por favor, ingrese la cantidad de cupos para cada horario.", []);
      return;
    }


    var data = {
      "nombre": this.nombre,
      "email": this.email,
      "direccion": this.direccion,
      "barrio": this.barrio,
      "telefono": this.telefono,
      "duracion": this.duracion,
      "lunes": this.lunes,
      "martes": this.martes,
      "miercoles": this.miercoles,
      "jueves": this.jueves,
      "viernes": this.viernes,
      "sabado": this.sabado,
      "domingo": this.domingo,
      "desde": this.desde,
      "hasta": this.hasta,
      "descanso": this.descanso,
      "cupos": this.cupos
    }

    this.NgxSpinnerService.show();
    this.NegocioService.guardarInfo(data).subscribe(data => {

      if(data["new_jwt"] != "0"){ // Reemplazo el JWT si amerita
        this.usuario.token = data["new_jwt"];
        this.usuario.username = this.email;
        localStorage.setItem("currentUser", JSON.stringify(this.usuario));
      }

      this.NgxSpinnerService.hide();
      this.ToastService.notificar("success", "Información guardada correctamente.", []);
    });


    
  }

}
