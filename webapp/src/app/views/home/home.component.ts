import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { Router, ActivatedRoute } from '@angular/router';
import { NegocioService } from "@services/negocio.service";
import { Utilidades } from "@helpers/utilidades";
import { AuthService } from "@services/auth.service";
import { ModalDirective } from 'ngx-bootstrap/modal';
import { Location } from '@angular/common';


@Component({
  selector: 'app-dashboard',
  templateUrl: './home2.component.html'
})
export class HomeComponent implements OnInit, AfterContentInit{
  @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  @ViewChild('modalTutorial') public modalTutorial: ModalDirective;
  @ViewChild('videoPlayer') videoplayer: ElementRef;


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
    private AuthService: AuthService,
    private ActivatedRoute: ActivatedRoute,
    private Location: Location
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
    setTimeout(() => { 
      if (this.Router.url == "/tutorial"){
        this.Location.replaceState("/");
        this.verTutorial();
      }
    }, 500);

   
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


  verTutorial(){
    this.modalTutorial.show();
    this.videoplayer.nativeElement.play();
  }

  cerrarTutorial(){
    this.videoplayer.nativeElement.pause();
    this.videoplayer.nativeElement.currentTime = 0;
    this.videoplayer.nativeElement.load();
    this.modalTutorial.hide();
  }



  irAAgendar(slug: string){
    this.Router.navigate(["/agendar/" + slug]); 
  }

  volver(){
    this.mostrando_sector_resultados = false;
  }




}




