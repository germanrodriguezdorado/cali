import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";
import { Router, ActivatedRoute } from '@angular/router';
import { NegocioService } from "@services/negocio.service";
import { Utilidades } from "@helpers/utilidades";
import { AuthService } from "@services/auth.service";
import { ModalDirective } from 'ngx-bootstrap/modal';
import { Location } from '@angular/common';
import { ToastService } from "@services/toast.service";


@Component({
  selector: 'app-dashboard',
  templateUrl: './home2.component.html'
})
export class HomeComponent implements OnInit, AfterContentInit{
  @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  @ViewChild('modalTutorial') public modalTutorial: ModalDirective;
  @ViewChild('videoPlayer') videoplayer: ElementRef;


  rubro: string;
  barrio: string;
  negocios: any;
  barrios_disponibles: string[];
  mostrando_sector_resultados: boolean;
  loading: boolean;
  iniciando_paso2: boolean;



  constructor(
    private NgxSpinnerService: NgxSpinnerService,
    private NegocioService: NegocioService,
    private Router: Router,
    public Utilidades: Utilidades,
    private AuthService: AuthService,
    private ActivatedRoute: ActivatedRoute,
    private Location: Location,
    private ToastService: ToastService,
    ) { }




  ngOnInit() {
    this.AuthService.logout();
    this.loading = false;
    this.rubro = "";
    this.barrio = "";
    this.negocios = [];
    this.mostrando_sector_resultados = false;
    this.barrios_disponibles = this.Utilidades.darBarriosDisponibles();
    this.iniciando_paso2 = true;
  }

  


  public ngAfterContentInit() {
    setTimeout(() => { 
      if (this.Router.url == "/tutorial"){
        this.Location.replaceState("/");
        this.verTutorial();
      }
    }, 500);

   
  }

  ir_a_paso2(){
    this.mostrando_sector_resultados = true;
  }

  buscar(){

    if(this.rubro == ""){
      this.ToastService.notificar("warning", "Seleccioná un rubro para comenzar tu búsqueda.", []);
      return;
    }

    this.loading = true;
    this.iniciando_paso2 = false;
    var data = {
      "rubro": this.rubro,
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
    this.Router.navigate(["/s/" + slug]); 
  }

  volver(){
    this.mostrando_sector_resultados = false;
    this.iniciando_paso2 = true;
    this.rubro = "";
    this.barrio = "";
  }


  irARedSocial(red_social: string): void{
    if(red_social=="instagram"){
      window.open("https://www.instagram.com/cali.uy", "_blank");
    }
    if(red_social=="facebook"){
      window.open("https://www.facebook.com/Caliuy-105118571893889", "_blank");
    }
    if(red_social=="youtube"){
      window.open("https://www.youtube.com/channel/UCBON5K4tBSDxh0Ax312Onrg", "_blank");
    }
  }




}




