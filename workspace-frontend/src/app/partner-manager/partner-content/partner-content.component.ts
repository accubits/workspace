import { Component, OnInit } from '@angular/core';
import { PartnerManagerDataService } from '../../shared/services/partner-manager-data.service'
import { PartnerManagerSandbox } from '../partner-manager.sandbox';
import {PartnerDataService} from '../../shared/services/partner-data.service';
import { Router } from '@angular/router';
import { CookieService } from 'ngx-cookie-service';



@Component({
  selector: 'app-partner-content',
  templateUrl: './partner-content.component.html',
  styleUrls: ['./partner-content.component.scss']
})
export class PartnerContentComponent implements OnInit {

  partner:string;
  showPartner='false';


  constructor(
    public partnerManagerSandbox : PartnerManagerSandbox,
    public partnerDataService :PartnerDataService,
    public partnerManagerDataService :PartnerManagerDataService,
    private router: Router,
    public cookieService: CookieService


  ) { }

  ngOnInit() {
      // Initial Executions 
    this.partnerManagerSandbox.getAllPartners();
  }

  /* Switch to Partners */
  switchPartners(partners):void{
    this.partnerManagerDataService.getPartner.name = partners.name;
    this.cookieService.set('partnerSlug',partners.partnerSlug);
    this.router.navigate(['/partner/organisation']);
   // this.partnerDataService.showPartner.show = true;
   this.cookieService.set('showPartner','true');


  }

  partnerDashboard():void{
    this.router.navigate(['/partner/organisation']);
    // this.partnerDataService.showPartner.show = true;


  }

}
