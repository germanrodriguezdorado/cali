import { INavData } from '@coreui/angular';

export const navItems: INavData[] = [
  {
    name: 'Propiedades',
    url: '/propiedades',
    icon: 'icon-home',
    children: [
      {
        name: 'Listado',
        url: '/propiedades/listado',
        icon: 'icon-list'
      },
      {
        name: 'Nueva',
        url: '/propiedades/nueva',
        icon: 'icon-plus'
      }
    ]
  }
];
