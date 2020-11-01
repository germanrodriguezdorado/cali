import { Component, ElementRef, ViewChild, OnInit, AfterContentInit, ViewContainerRef } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute, Router } from '@angular/router';
import { DatePipe } from "@angular/common";
import { Utilidades } from "@helpers/utilidades";
import { NegocioService } from "@services/negocio.service";



@Component({
  selector: 'app-dashboard',
  templateUrl: './agendarConfirmacion.component.html',
  providers: [DatePipe]
})
export class AgendarConfirmacionComponent implements OnInit{
  // @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  @ViewChild("inputMail", { read: ViewContainerRef }) inputMail:ElementRef;
  token: string;
  mostrar_panel: boolean;
  negocio: string;
  direccion: string;
  fecha: string;
  horario: string;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private ActivatedRoute: ActivatedRoute,
    public Utilidades: Utilidades,
    private NegocioService: NegocioService,
    private DatePipe: DatePipe,
    private Router: Router
    ) { }




  ngOnInit() {
    this.negocio = "";
    this.direccion = "";
    this.fecha = "";
    this.horario = "";
    this.ActivatedRoute.params.subscribe(params => {
      this.token = params["token"];
    });
    this.checkear();
  }




  checkear(){
    this.NgxSpinnerService.show();
    var data = {
      "token": this.token
    }
    this.NegocioService.agendarConfirmacion(data).subscribe(respuesta => {
      this.NgxSpinnerService.hide();
      if(respuesta["cliente_email"] != ""){
        this.negocio = respuesta["negocio"];
        this.direccion = respuesta["direccion"];
        this.fecha = respuesta["fecha"];
        this.horario = respuesta["horario"];
        this.mostrar_panel = true;
      }else{
        this.Router.navigate(["/"]);
      }
    });
  }




}




