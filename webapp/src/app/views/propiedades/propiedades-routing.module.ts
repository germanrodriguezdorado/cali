import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { ListadoComponent } from './listado.component';

const routes: Routes = [
  {
    path: '', data: { title: 'Base' },
    children: [
      { path: '', redirectTo: 'listado' },
      { path: 'listado', component: ListadoComponent, data: { title: 'Listado' }},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class BaseRoutingModule {}
