import { Component, OnInit } from '@angular/core';
import { ToastService } from "@services/toast.service";



@Component({
  templateUrl: 'informacion.component.html'
})
export class NegocioInformacionComponent implements OnInit {
  nombre: string;
  direccion: string;
  telefono: string
  zona: string;
  duracion: string;

  
  constructor(
    private ToastService: ToastService
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
    this.nombre = "Peluquería Morini";
    this.direccion = "Rivera 2367 esq. Verdi";
    this.telefono = "098438763";
    this.zona = "Pocitos";
    this.duracion = "1 hora";
  }

  guardar(){
    this.ToastService.notificar("warning", "Por favor, complete todos los campos requeridos.", []);
  }

}
