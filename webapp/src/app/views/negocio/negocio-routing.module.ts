import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { NegocioAgendaComponent } from './agenda/agenda.component';
import { TrabajosComponent } from './trabajos/trabajos.component';
import { NegocioInformacionComponent } from './informacion/informacion.component';
import { HorariosComponent } from './horarios/horarios.component';

import { AuthGuard } from '@guards/auth.guard';

const routes: Routes = [
  {
    path: '', data: { title: 'Mi negocio' },
    children: [
      { path: '', redirectTo: 'listado' },
      { path: 'agenda', component: NegocioAgendaComponent, canActivate: [AuthGuard], data: { title: 'Agenda', roles: [1] }},
      { path: 'trabajos', component: TrabajosComponent, canActivate: [AuthGuard], data: { title: 'Trabajos', roles: [1] }},
      { path: 'informacion', component: NegocioInformacionComponent, canActivate: [AuthGuard], data: { title: 'Información', roles: [1] }},
      { path: 'horarios', component: HorariosComponent, canActivate: [AuthGuard], data: { title: 'Horarios', roles: [1] }},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NegocioRoutingModule {}
