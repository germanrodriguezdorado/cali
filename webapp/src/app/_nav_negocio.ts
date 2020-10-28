import { INavData } from '@coreui/angular';

export const navItemsNegocio: INavData[] = [
  {
    name: 'Información',
    url: '/negocio/informacion',
    icon: 'icon-info',
  },
  // {
  //   name: 'Trabajos',
  //   url: '/negocio/trabajos',
  //   icon: 'icon-briefcase', 
  // },
  // {
  //   name: 'Horarios',
  //   url: '/negocio/horarios',
  //   icon: 'icon-clock', 
  // },
  {
    name: 'Agenda',
    url: '/negocio/agenda',
    icon: 'icon-calendar',
    // children: [
    //   {
    //     name: 'Listado',
    //     url: '/negocio/listado',
    //     icon: 'icon-list'
    //   },
    //   {
    //     name: 'Nueva',
    //     url: '/negocio/nueva',
    //     icon: 'icon-plus'
    //   }
    // ]
    
  }
];
