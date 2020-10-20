import { Component, ElementRef, ViewChild, OnInit, AfterContentInit, ViewContainerRef } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute, Router } from '@angular/router';
import { DatePipe } from "@angular/common";
import { Utilidades } from "@helpers/utilidades";


@Component({
  selector: 'app-dashboard',
  templateUrl: './agendar.component.html',
  providers: [DatePipe]
})
export class AgendarComponent implements OnInit, AfterContentInit{
  // @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  @ViewChild("inputMail", { read: ViewContainerRef }) inputMail:ElementRef;
  negocio: string;
  mostrar_resultados: boolean;
  busqueda: string;
  paso: number;
  email: string;
  fecha: Date;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private ActivatedRoute: ActivatedRoute,
    public Utilidades: Utilidades 
    ) { }




  ngOnInit() {
    this.negocio = "";
    this.mostrar_resultados = true;
    this.busqueda = "";
    this.paso = 1;
    this.email = "";
    this.fecha = new Date();


    this.ActivatedRoute.params.subscribe(params => {
      this.negocio = params["negocio"];
    });


  }


  public ngAfterContentInit() {
    //setTimeout(() => { this.inputMail.nativeElement.focus();}, 500);
  }



  buscar(){

    this.NgxSpinnerService.show();

    setTimeout(() => {
      this.mostrar_resultados = true;
      this.NgxSpinnerService.hide();
    }, 500);

    
  }


  irApaso1(){
    this.paso = 1;
  }

  irApaso2(){
    this.paso = 2;
    //this.inputMail.nativeElement.focus();
    //setTimeout(() => { this.inputMail.nativeElement.focus();}, 1000);
  }

  irApaso3(){
    this.paso = 3;
  }




}




