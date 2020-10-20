import { Component, OnInit } from '@angular/core';
import { ToastService } from "@services/toast.service";
import { Utilidades } from "@helpers/utilidades";



@Component({
  templateUrl: 'horarios.component.html'
})
export class HorariosComponent implements OnInit {

  horarios_disponibles: string[];
  dias: string[];
  desde: string;
  hasta: string;
  descanso: string;
  
  
  constructor(
    private ToastService: ToastService,
    public Utilidades: Utilidades 
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
    this.horarios_disponibles = this.Utilidades.darHorariosDisponibles();
    this.dias = ["lunes", "martes", "jueves"];
    this.desde = "08:00";
    this.hasta = "18:00";
    this.descanso = "De 12 a 13";

  }

  guardar(){
    this.ToastService.notificar("warning", "Por favor, complete todos los campos requeridos.", []);
  }

}
