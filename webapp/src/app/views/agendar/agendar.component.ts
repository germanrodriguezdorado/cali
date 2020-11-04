import { Component, ElementRef, ViewChild, OnInit, AfterContentInit, ViewContainerRef } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute, Router } from '@angular/router';
import { DatePipe } from "@angular/common";
import { Utilidades } from "@helpers/utilidades";
import { NegocioService } from "@services/negocio.service";



@Component({
  selector: 'app-dashboard',
  templateUrl: './agendar.component.html',
  providers: [DatePipe]
})
export class AgendarComponent implements OnInit, AfterContentInit{
  // @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  @ViewChild("inputMail", { read: ViewContainerRef }) inputMail:ElementRef;
  slug: string;
  nombre_negocio: string;
  direccion_negocio: string;
  telefono_negocio: string;
  mostrar_resultados: boolean;
  paso: number;
  email: string;
  fecha: Date;
  min: Date;
  horarios: any[];
  horario_seleccionado: string;
  paso2_mensaje: string;
  loading: boolean;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private ActivatedRoute: ActivatedRoute,
    public Utilidades: Utilidades,
    private NegocioService: NegocioService,
    private DatePipe: DatePipe,
    private Router: Router
    ) { }




  ngOnInit() {

    
    this.loading = false;
    this.paso2_mensaje = "";
    this.slug = "";
    this.nombre_negocio = "Cargando...";
    this.direccion_negocio = "";
    this.telefono_negocio = "";
    this.mostrar_resultados = true;
    this.paso = 1;
    this.email = "";
    this.fecha = new Date();
    this.horarios = [];
    this.horario_seleccionado = "";


    let dte = new Date();
    dte.setDate(dte.getDate() - 2);
    this.min = dte;
    //console.log(this.min)


    this.ActivatedRoute.params.subscribe(params => {
      this.slug = params["negocio"];
    });

    this.buscar();


    
    


  }


  public ngAfterContentInit() {
    //setTimeout(() => { this.inputMail.nativeElement.focus();}, 500);
  }



  buscar(){
    this.loading = true;
    this.horarios = []
    var data2 = {
      "slug": this.slug,
      "fecha": this.DatePipe.transform(this.fecha, "yyyy-MM-dd")
    }
    this.NegocioService.buscarHorarios(data2).subscribe(respuesta => {
      this.loading = false;
      if(respuesta["nombre_negocio"] != ""){
        this.nombre_negocio = respuesta["nombre_negocio"];
        this.telefono_negocio = respuesta["telefono_negocio"];
        this.direccion_negocio = respuesta["direccion_negocio"];
        this.horarios = respuesta["horarios"];
      }else{
        this.Router.navigate(["/"]);
      }
    });
  }




  irApaso1(){
    this.paso = 1;
  }

  irApaso2(horario_seleccionado: string){
    this.horario_seleccionado = horario_seleccionado;
    this.paso = 2;
  }

  agendar(){
    this.paso2_mensaje = "";
    this.loading = true;
    var data = {
      "slug": this.slug,
      "fecha": this.DatePipe.transform(this.fecha, "yyyy-MM-dd"),
      "horario": this.horario_seleccionado,
      "email": this.email
    }
    this.NegocioService.agendar(data).subscribe(respuesta => {
      this.loading = false;

      if(respuesta == "1"){ // OK
        this.paso = 3;
      }

      if(respuesta == "-1"){ // ERROR
        alert("error");
      }

      if(respuesta == "-2"){ // Mail ya tiene agenda
        this.paso2_mensaje = "Ya tienes una reserva en " + this.nombre_negocio + ". Puedes comunicarte con el local al " + this.telefono_negocio;
      }

      if(respuesta == "-3"){ // Email bloqueado
        this.paso2_mensaje = "No es posible realizar tu reserva";
      }

    });
  }




}




