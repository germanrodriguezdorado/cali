import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ListadoComponent } from './listado.component';
import { NuevaComponent } from './nueva.component';

import { AuthGuard } from '@guards/auth.guard';

const routes: Routes = [
  {
    path: '', data: { title: 'Propiedades' },
    children: [
      { path: '', redirectTo: 'listado' },
      { path: 'listado', component: ListadoComponent, canActivate: [AuthGuard], data: { title: 'Listado' }},
      { path: 'nueva', component: NuevaComponent, canActivate: [AuthGuard], data: { title: 'Nueva' }},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class PropiedadesRoutingModule {}
