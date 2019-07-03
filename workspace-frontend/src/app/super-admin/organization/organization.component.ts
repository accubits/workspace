import { Component, OnInit } from '@angular/core';
import { SuperAdminDataService } from '../../shared/services/super-admin-data.service';

@Component({
  selector: 'app-organization',
  templateUrl: './organization.component.html',
  styleUrls: ['./organization.component.scss']
})
export class OrganizationComponent implements OnInit {

  constructor(
    public superAdminDataService: SuperAdminDataService
  ) { }

  ngOnInit() {
  }
  
  morepop(event){
    this.superAdminDataService.more.show = true;
    event.stopPropagation();
  }

  showpop(){
    this.superAdminDataService.orgDetailPop.show = true;
  }

  check(event){
    event.stopPropagation();
  }
  closeMore(event){
    this.superAdminDataService.more.show = false;
    event.stopPropagation();
  }



}
