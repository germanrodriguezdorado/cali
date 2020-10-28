import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { Router } from '@angular/router';
import { NegocioService } from "@services/negocio.service";


@Component({
  selector: 'app-dashboard',
  templateUrl: './home.component.html'
})
export class HomeComponent implements OnInit, AfterContentInit{
  @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  nombre: string;
  barrio: string;
  negocios: any;
  mostrando_sector_resultados: boolean;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private NegocioService: NegocioService,
    private Router: Router,
    ) { }




  ngOnInit() {
    this.nombre = "";
    this.barrio = "";
    this.negocios = [];
    this.mostrando_sector_resultados = false;
  }


  public ngAfterContentInit() {
    setTimeout(() => { this.inputBusqueda.nativeElement.focus();}, 500);
  }



  buscar(){
    this.NgxSpinnerService.show();
    var data = {
      "nombre": this.nombre,
      "barrio": this.barrio
    }
    this.NegocioService.buscar(data).subscribe(data => {
      this.negocios = data;
      this.mostrando_sector_resultados = true;
      this.NgxSpinnerService.hide();
    });

    

    
  }



  irAAgendar(slug: string){
    this.Router.navigate(["/agendar/" + slug]); 
  }




}




