import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { AgendaComponent } from './agenda/agenda.component';
import { TrabajosComponent } from './trabajos/trabajos.component';
import { InformacionComponent } from './informacion/informacion.component';
import { HorariosComponent } from './horarios/horarios.component';

import { AuthGuard } from '@guards/auth.guard';

const routes: Routes = [
  {
    path: '', data: { title: 'Mi negocio' },
    children: [
      { path: '', redirectTo: 'listado' },
      { path: 'agenda', component: AgendaComponent, canActivate: [AuthGuard], data: { title: 'Agenda' }},
      { path: 'trabajos', component: TrabajosComponent, canActivate: [AuthGuard], data: { title: 'Trabajos' }},
      { path: 'informacion', component: InformacionComponent, canActivate: [AuthGuard], data: { title: 'Información' }},
      { path: 'horarios', component: HorariosComponent, canActivate: [AuthGuard], data: { title: 'Horarios' }},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NegocioRoutingModule {}
