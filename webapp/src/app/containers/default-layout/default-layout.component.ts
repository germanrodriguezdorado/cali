import { Component, OnInit } from '@angular/core';
import { navItemsNegocio } from '../../_nav_negocio';
import { navItemsAdmin } from '../../_nav_admin';

@Component({
  selector: 'app-dashboard',
  templateUrl: './default-layout.component.html'
})
export class DefaultLayoutComponent implements OnInit {
  public sidebarMinimized = false;
  public navItems: any;
  public usuario: any;

  toggleMinimize(e) {
    this.sidebarMinimized = e;
  }

  ngOnInit(){
    this.usuario = JSON.parse(localStorage.getItem("currentUser")); 
    if(this.usuario.tipo == 1) this.navItems = navItemsNegocio;
    if(this.usuario.tipo == 2) this.navItems = navItemsAdmin;
  }
}



