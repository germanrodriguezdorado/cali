import { Component, ElementRef, ViewChild, OnInit, AfterContentInit } from '@angular/core';
import { NgxSpinnerService } from "ngx-spinner";


@Component({
  selector: 'app-dashboard',
  templateUrl: './home.component.html'
})
export class HomeComponent implements OnInit, AfterContentInit{
  @ViewChild("inputBusqueda") inputBusqueda:ElementRef;
  busqueda: string;
  mostrando_sector_resultados: boolean;



  constructor(
    private NgxSpinnerService: NgxSpinnerService
    ) { }




  ngOnInit() {
    this.busqueda = "";
    this.mostrando_sector_resultados = false;
  }


  public ngAfterContentInit() {
    setTimeout(() => { this.inputBusqueda.nativeElement.focus();}, 500);
  }



  buscar(){

    this.NgxSpinnerService.show();

    setTimeout(() => {
      this.mostrando_sector_resultados = true;
      this.busqueda = "";
      this.NgxSpinnerService.hide();
    }, 500);

    
  }




}




