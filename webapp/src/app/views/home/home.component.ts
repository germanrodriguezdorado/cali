import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { Router } from '@angular/router';
import { NegocioService } from "@services/negocio.service";
import { Utilidades } from "@helpers/utilidades";
import { AuthService } from "@services/auth.service";


@Component({
  selector: 'app-dashboard',
  templateUrl: './home.component.html'
})
export class HomeComponent implements OnInit, AfterContentInit{
  @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  nombre: string;
  barrio: string;
  negocios: any;
  barrios_disponibles: string[];
  mostrando_sector_resultados: boolean;
  loading: boolean;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private NegocioService: NegocioService,
    private Router: Router,
    public Utilidades: Utilidades,
    private AuthService: AuthService
    ) { }




  ngOnInit() {
    this.AuthService.logout();
    this.loading = false;
    this.nombre = "";
    this.barrio = "";
    this.negocios = [];
    this.mostrando_sector_resultados = false;
    this.barrios_disponibles = this.Utilidades.darBarriosDisponibles();
  }


  public ngAfterContentInit() {
    setTimeout(() => { this.inputBusqueda.nativeElement.focus();}, 500);
  }



  buscar(){
    this.loading = true;
    var data = {
      "nombre": this.nombre,
      "barrio": this.barrio
    }
    this.NegocioService.buscar(data).subscribe(data => {
      this.negocios = data;
      this.mostrando_sector_resultados = true;
      this.loading = false;
    });

    

    
  }



  irAAgendar(slug: string){
    this.Router.navigate(["/agendar/" + slug]); 
  }




}




