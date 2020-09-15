import { INavData } from '@coreui/angular';

export const navItems: INavData[] = [
  {
    name: 'Propiedades',
    url: '/propiedades',
    icon: 'icon-puzzle',
    children: [
      {
        name: 'Listado',
        url: '/propiedades/listado',
        icon: 'icon-puzzle'
      }
    ]
  }
];
