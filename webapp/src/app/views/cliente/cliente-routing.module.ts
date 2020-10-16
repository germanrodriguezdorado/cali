import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ClienteAgendaComponent } from './agenda/agenda.component';
import { ClienteInformacionComponent } from './informacion/informacion.component';

import { AuthGuard } from '@guards/auth.guard';

const routes: Routes = [
  {
    path: '', data: { title: 'Cliente' },
    children: [
      { path: '', redirectTo: 'listado' },
      { path: 'agenda', component: ClienteAgendaComponent, canActivate: [AuthGuard], data: { title: 'Agenda', roles: [2] }},
      { path: 'informacion', component: ClienteInformacionComponent, canActivate: [AuthGuard], data: { title: 'Información', roles: [2] }}
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class NegocioRoutingModule {}
