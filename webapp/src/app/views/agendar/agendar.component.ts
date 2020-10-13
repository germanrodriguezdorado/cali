import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { ActivatedRoute, Router } from '@angular/router';


@Component({
  selector: 'app-dashboard',
  templateUrl: './agendar.component.html'
})
export class AgendarComponent implements OnInit, AfterContentInit{
  // @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  negocio: string;
  mostrar_resultados: boolean;
  busqueda: string;
  paso: number;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private ActivatedRoute: ActivatedRoute, 
    ) { }




  ngOnInit() {
    this.negocio = "";
    this.mostrar_resultados = false;
    this.busqueda = "";
    this.paso = 1;


    this.ActivatedRoute.params.subscribe(params => {
      this.negocio = params["negocio"];
    });


  }


  public ngAfterContentInit() {
    //setTimeout(() => { this.inputBusqueda.nativeElement.focus();}, 500);
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
  }




}




