import { Component, OnInit } from '@angular/core';
import { Router, NavigationEnd } from '@angular/router';
import { environment } from '@environments/environment';
import { VersionCheckService } from "@services/version-check.service";

@Component({
  // tslint:disable-next-line
  selector: 'body',
  template: '<router-outlet></router-outlet>'
})
export class AppComponent implements OnInit {
  constructor(private router: Router, private VersionCheckService: VersionCheckService) { }

  ngOnInit() {

    if(environment.versionCheckEnabled){
      this.VersionCheckService.initVersionCheck("version.json", environment.versionCheckFrecuency);
    } 

    
    this.router.events.subscribe((evt) => {
      if (!(evt instanceof NavigationEnd)) {
        return;
      }
      window.scrollTo(0, 0);
    });
  }
}
